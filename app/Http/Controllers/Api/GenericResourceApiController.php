<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * CRUD generik untuk modul staf via API Flutter.
 * Aturan validasi dibangun otomatis dari kolom DB ∩ $fillable model.
 * Hanya token STAF (guard web) yang boleh akses; token pasien ditolak (403).
 * RBAC rinci tetap di web; endpoint ini untuk aplikasi staf internal tepercaya.
 */
class GenericResourceApiController extends Controller
{
    private function resolve(string $resource): array
    {
        $config = config('api_resources.' . $resource);
        abort_unless($config && class_exists($config['model']), 404, 'Resource tidak dikenal.');

        return $config;
    }

    private function model(array $config): Model
    {
        /** @var Model $model */
        $model = new $config['model'];

        return $model;
    }

    public function index(Request $request, string $resource): JsonResponse
    {
        abort_unless($request->user(), 403, 'Hanya token staf.');
        $config = $this->resolve($resource);
        $model = $this->model($config);
        $query = $model->newQuery();

        if (! empty($config['with'])) {
            $query->with($config['with']);
        }

        if ($search = trim((string) $request->get('search', ''))) {
            $query->where(function ($q) use ($model, $config, $search) {
                foreach ($this->searchColumns($model, $config) as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        foreach ($this->filterableColumns($model) as $col) {
            if ($request->has($col)) {
                $query->where($col, $request->get($col));
            }
        }

        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
        $orderCol = Schema::hasColumn($model->getTable(), 'created_at') ? 'created_at' : $model->getKeyName();

        return response()->json($query->orderByDesc($orderCol)->paginate($perPage));
    }

    public function store(Request $request, string $resource): JsonResponse
    {
        abort_unless($request->user(), 403, 'Hanya token staf.');
        $config = $this->resolve($resource);
        $model = $this->model($config);

        try {
            $record = $model->newQuery()->create($request->validate($this->rules($model, false)));
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Data tidak dapat disimpan. Periksa field wajib tabel ini.', 'error' => $e->getMessage()], 422);
        }

        return response()->json($record->fresh(), 201);
    }

    public function show(Request $request, string $resource, string $id): JsonResponse
    {
        abort_unless($request->user(), 403, 'Hanya token staf.');
        $config = $this->resolve($resource);
        $query = $this->model($config)->newQuery();
        if (! empty($config['with'])) {
            $query->with($config['with']);
        }

        return response()->json($query->findOrFail((int) $id));
    }

    public function update(Request $request, string $resource, string $id): JsonResponse
    {
        abort_unless($request->user(), 403, 'Hanya token staf.');
        $config = $this->resolve($resource);
        $model = $this->model($config);
        $record = $model->newQuery()->findOrFail((int) $id);
        try {
            $record->update($request->validate($this->rules($model, true)));
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Data tidak dapat diubah. Periksa field tabel ini.', 'error' => $e->getMessage()], 422);
        }

        return response()->json($record->fresh());
    }

    public function destroy(Request $request, string $resource, string $id): JsonResponse
    {
        abort_unless($request->user(), 403, 'Hanya token staf.');
        $config = $this->resolve($resource);
        $this->model($config)->newQuery()->findOrFail((int) $id)->delete();

        return response()->json(null, 204);
    }

    /** @return array<string, string|array> */
    private function rules(Model $model, bool $isUpdate): array
    {
        $fillable = $model->getFillable();
        $rules = [];
        foreach (Schema::getColumns($model->getTable()) as $col) {
            $name = $col['name'];
            if (! in_array($name, $fillable, true)) {
                continue;
            }
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at', 'remember_token'], true)) {
                continue;
            }
            $rules[$name] = $this->columnRule($col, $isUpdate);
        }

        return $rules;
    }

    private function columnRule(array $col, bool $isUpdate): string|array
    {
        $req = $isUpdate ? 'sometimes' : 'sometimes';
        $nullable = ($col['nullable'] ?? true) ? '|nullable' : '';
        $type = strtolower($col['type_name'] ?? '');
        $raw = strtolower($col['type'] ?? '');

        if (($col['name'] ?? '') === 'password') {
            return $req . '|string|min:6|max:255';
        }
        if (($col['name'] ?? '') === 'email') {
            return $req . $nullable . '|email|max:255';
        }

        return match (true) {
            $type === 'boolean' || $raw === 'tinyint(1)' => $req . $nullable . '|boolean',
            in_array($type, ['integer', 'bigint', 'mediumint', 'smallint', 'tinyint'], true) => $req . $nullable . '|integer',
            in_array($type, ['decimal', 'float', 'double'], true) => $req . $nullable . '|numeric',
            in_array($type, ['date', 'datetime', 'datetimetz', 'timestamp', 'time', 'year'], true) => $req . $nullable . '|date',
            $type === 'json' => $req . $nullable . '|array',
            $type === 'enum' => [$req . $nullable, 'in:' . implode(',', $this->enumValues($raw))],
            str_contains($raw, 'char') => $req . $nullable . '|string|max:' . $this->charLength($raw),
            in_array($type, ['text', 'mediumtext', 'longtext'], true) => $req . $nullable . '|string',
            default => $req . $nullable . '|string|max:255',
        };
    }

    /** @return string[] */
    private function enumValues(string $raw): array
    {
        preg_match_all("/'([^']+)'/", $raw, $m);

        return $m[1] ?? [];
    }

    private function charLength(string $raw): int
    {
        if (preg_match('/\((\d+)\)/', $raw, $m)) {
            return max(1, (int) $m[1]);
        }

        return 255;
    }

    /** @return string[] */
    private function searchColumns(Model $model, array $config): array
    {
        if (! empty($config['search'])) {
            return $config['search'];
        }
        $candidates = [];
        foreach (Schema::getColumns($model->getTable()) as $col) {
            $name = strtolower($col['name']);
            $type = strtolower($col['type_name'] ?? '');
            if (! in_array($type, ['string', 'varchar', 'char', 'text', 'mediumtext', 'longtext'], true)) {
                continue;
            }
            if (preg_match('/name|title|code|nomor|number|phone|email|nik|subject|label|diagnos|complaint/', $name)) {
                $candidates[] = $col['name'];
            }
            if (count($candidates) >= 6) {
                break;
            }
        }

        return $candidates !== [] ? $candidates : ['id'];
    }

    /** @return string[] */
    private function filterableColumns(Model $model): array
    {
        $out = [];
        foreach (Schema::getColumns($model->getTable()) as $col) {
            $type = strtolower($col['type_name'] ?? '');
            if (in_array($col['name'], $model->getFillable(), true)
                && in_array($type, ['integer', 'bigint', 'boolean', 'date', 'datetime'], true)) {
                $out[] = $col['name'];
            }
            if (count($out) >= 12) {
                break;
            }
        }

        return $out;
    }
}

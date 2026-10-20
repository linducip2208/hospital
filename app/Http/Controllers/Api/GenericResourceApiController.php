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
        $config = $this->resolve($resource);
        $this->authorizeResource($request, $config, 'read');
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
        $page = $query->orderByDesc($orderCol)->paginate($perPage);
        $page->getCollection()->transform(fn ($item) => $this->scrub($item->toArray()));

        return response()->json($page);
    }

    public function store(Request $request, string $resource): JsonResponse
    {
        $config = $this->resolve($resource);
        $this->authorizeResource($request, $config, 'write');
        $model = $this->model($config);

        try {
            $record = $model->newQuery()->create($request->validate($this->rules($model, false)));
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Data tidak dapat disimpan. Periksa field wajib tabel ini.', 'error' => $e->getMessage()], 422);
        }
        \App\Services\ActivityLogger::log('created', $record, null, ['via' => 'api', 'resource' => $resource]);

        return response()->json($this->scrub($record->fresh()->toArray()), 201);
    }

    public function show(Request $request, string $resource, string $id): JsonResponse
    {
        $config = $this->resolve($resource);
        $this->authorizeResource($request, $config, 'read');
        $query = $this->model($config)->newQuery();
        if (! empty($config['with'])) {
            $query->with($config['with']);
        }

        return response()->json($this->scrub($query->findOrFail((int) $id)->toArray()));
    }

    public function update(Request $request, string $resource, string $id): JsonResponse
    {
        $config = $this->resolve($resource);
        $this->authorizeResource($request, $config, 'write');
        $model = $this->model($config);
        $record = $model->newQuery()->findOrFail((int) $id);
        try {
            $record->update($request->validate($this->rules($model, true)));
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Data tidak dapat diubah. Periksa field tabel ini.', 'error' => $e->getMessage()], 422);
        }
        \App\Services\ActivityLogger::log('updated', $record, null, ['via' => 'api', 'resource' => $resource]);

        return response()->json($this->scrub($record->fresh()->toArray()));
    }

    public function destroy(Request $request, string $resource, string $id): JsonResponse
    {
        $config = $this->resolve($resource);
        $this->authorizeResource($request, $config, 'delete');
        $record = $this->model($config)->newQuery()->findOrFail((int) $id);
        $record->delete();
        \App\Services\ActivityLogger::log('deleted', $record, null, ['via' => 'api', 'resource' => $resource]);

        return response()->json(null, 204);
    }

    private function authorizeResource(Request $request, array $config, string $action): void
    {
        $user = $request->user();
        abort_unless($user, 403, 'Hanya token staf.');
        // admin & developer selalu lolos (konsisten dengan User::hasPermission).
        if (in_array($user->role, ['admin', 'developer'], true)) {
            return;
        }
        $key = $action === 'read' ? 'read_roles' : ($action === 'delete' ? 'delete_roles' : 'write_roles');
        if (array_key_exists($key, $config)) {
            // null = semua staf boleh; array = hanya peran tersebut.
            if (is_array($config[$key])) {
                abort_unless(in_array($user->role, $config[$key], true), 403, 'Peran Anda tidak boleh mengakses modul ini via API.');
            }

            return;
        }
        // Default hapus = admin/developer saja (anti penghapusan massal via API).
        // Baca/tulis default = semua staf (sama seperti akses web).
        if ($action === 'delete') {
            abort(403, 'Hapus data via API hanya untuk admin/developer.');
        }
    }

    /** Buang field sensitif dari payload generik (defense in depth). */
    private function scrub(array $payload): array
    {
        foreach ($payload as $key => $value) {
            if (is_string($key) && preg_match('/password|token|secret|api_key|remember/i', $key)) {
                unset($payload[$key]);
            }
        }

        return $payload;
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

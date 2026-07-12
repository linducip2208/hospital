<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\DrugDestruction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DrugDestructionController extends Controller
{
    public function index(): View
    {
        $destructions = DrugDestruction::with('items')->latest()->paginate(15);
        return view('drug-destructions.index', compact('destructions'));
    }

    public function create(): View
    {
        return view('drug-destructions.create', [
            'drugs' => Drug::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $destruction = DB::transaction(function () use ($validated) {
            $items = $validated['items'];
            unset($validated['items']);
            $validated['destruction_no'] = $this->generateNo();
            $destruction = DrugDestruction::create($validated);
            foreach ($items as $item) {
                $destruction->items()->create($item);
            }
            return $destruction;
        });
        return redirect()->route('drug-destructions.show', $destruction)->with('success', 'Berita acara dibuat.');
    }

    public function show(DrugDestruction $drugDestruction): View
    {
        $drugDestruction->load('items.drug');
        return view('drug-destructions.show', ['destruction' => $drugDestruction]);
    }

    public function edit(DrugDestruction $drugDestruction): View
    {
        $drugDestruction->load('items');
        return view('drug-destructions.edit', [
            'destruction' => $drugDestruction,
            'drugs' => Drug::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, DrugDestruction $drugDestruction): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        DB::transaction(function () use ($validated, $drugDestruction) {
            $items = $validated['items'];
            unset($validated['items']);
            $drugDestruction->update($validated);
            $drugDestruction->items()->delete();
            foreach ($items as $item) {
                $drugDestruction->items()->create($item);
            }
        });
        return redirect()->route('drug-destructions.show', $drugDestruction)->with('success', 'BA diperbarui.');
    }

    public function destroy(DrugDestruction $drugDestruction): RedirectResponse
    {
        $drugDestruction->delete();
        return redirect()->route('drug-destructions.index')->with('success', 'BA dihapus.');
    }

    public function print(DrugDestruction $drugDestruction): View
    {
        $drugDestruction->load('items.drug');
        return view('drug-destructions.print', ['destruction' => $drugDestruction]);
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'destruction_date' => 'required|date',
            'location' => 'required|string|max:255',
            'method' => 'required|string|max:255',
            'responsible_pharmacist' => 'required|string|max:255',
            'witness_name_1' => 'nullable|string|max:255',
            'witness_role_1' => 'nullable|string|max:100',
            'witness_name_2' => 'nullable|string|max:255',
            'witness_role_2' => 'nullable|string|max:100',
            'reason' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_id' => 'nullable|exists:drugs,id',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.batch_no' => 'nullable|string|max:100',
            'items.*.expired_at' => 'nullable|date',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.reason' => 'nullable|string|max:255',
        ]);
    }

    private function generateNo(): string
    {
        $count = DrugDestruction::whereDate('created_at', today())->count() + 1;
        return sprintf('BAP/%s/%04d', now()->format('Ymd'), $count);
    }
}

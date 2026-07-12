<?php

namespace App\Http\Controllers;

use App\Models\CostEstimate;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CostEstimateController extends Controller
{
    public function index(): View
    {
        $estimates = CostEstimate::with(['patient', 'doctor', 'items'])->latest()->paginate(15);
        return view('cost-estimates.index', compact('estimates'));
    }

    public function create(): View
    {
        return view('cost-estimates.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $estimate = DB::transaction(function () use ($validated) {
            $items = $validated['items'];
            unset($validated['items']);
            $validated['estimate_no'] = $this->generateNo();
            $validated['total_amount'] = collect($items)->sum(fn ($i) => (float) ($i['subtotal'] ?? 0));
            $estimate = CostEstimate::create($validated);
            foreach ($items as $item) {
                $estimate->items()->create($item);
            }
            return $estimate;
        });
        return redirect()->route('cost-estimates.show', $estimate)->with('success', 'Estimasi biaya dibuat.');
    }

    public function show(CostEstimate $costEstimate): View
    {
        $costEstimate->load(['patient', 'doctor', 'items']);
        return view('cost-estimates.show', ['estimate' => $costEstimate]);
    }

    public function edit(CostEstimate $costEstimate): View
    {
        $costEstimate->load('items');
        return view('cost-estimates.edit', [
            'estimate' => $costEstimate,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, CostEstimate $costEstimate): RedirectResponse
    {
        $validated = $this->validateRequest($request, true);
        DB::transaction(function () use ($validated, $costEstimate) {
            $items = $validated['items'];
            unset($validated['items']);
            $validated['total_amount'] = collect($items)->sum(fn ($i) => (float) ($i['subtotal'] ?? 0));
            $costEstimate->update($validated);
            $costEstimate->items()->delete();
            foreach ($items as $item) {
                $costEstimate->items()->create($item);
            }
        });
        return redirect()->route('cost-estimates.show', $costEstimate)->with('success', 'Estimasi diperbarui.');
    }

    public function destroy(CostEstimate $costEstimate): RedirectResponse
    {
        $costEstimate->delete();
        return redirect()->route('cost-estimates.index')->with('success', 'Estimasi dihapus.');
    }

    public function print(CostEstimate $costEstimate): View
    {
        $costEstimate->load(['patient', 'doctor', 'items']);
        return view('cost-estimates.print', ['estimate' => $costEstimate]);
    }

    private function validateRequest(Request $request, bool $forUpdate = false): array
    {
        $rules = [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'estimate_date' => 'required|date',
            'procedure_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ];
        if ($forUpdate) {
            $rules['status'] = 'required|in:draft,sent,approved,rejected,completed';
        }
        return $request->validate($rules);
    }

    private function generateNo(): string
    {
        $count = CostEstimate::whereDate('created_at', today())->count() + 1;
        return sprintf('EST/%s/%04d', now()->format('Ymd'), $count);
    }
}

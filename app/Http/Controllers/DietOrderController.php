<?php

namespace App\Http\Controllers;

use App\Models\DietOrder;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DietOrderController extends Controller
{
    public function index(): View
    {
        $orders = DietOrder::with(['patient', 'doctor'])->latest()->paginate(15);
        return view('diet-orders.index', [
            'orders' => $orders,
            'types' => DietOrder::DIET_TYPES,
        ]);
    }

    public function create(): View
    {
        return view('diet-orders.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'types' => DietOrder::DIET_TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['order_no'] = sprintf('DT/%s/%04d', now()->format('Ymd'), DietOrder::whereDate('created_at', today())->count() + 1);
        DietOrder::create($validated);
        return redirect()->route('diet-orders.index')->with('success', 'Order diet dibuat.');
    }

    public function show(DietOrder $dietOrder): View
    {
        return view('diet-orders.show', ['order' => $dietOrder->load(['patient', 'doctor'])]);
    }

    public function edit(DietOrder $dietOrder): View
    {
        return view('diet-orders.edit', [
            'order' => $dietOrder,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'types' => DietOrder::DIET_TYPES,
        ]);
    }

    public function update(Request $request, DietOrder $dietOrder): RedirectResponse
    {
        $dietOrder->update($this->validateRequest($request));
        return redirect()->route('diet-orders.show', $dietOrder)->with('success', 'Diperbarui.');
    }

    public function destroy(DietOrder $dietOrder): RedirectResponse
    {
        $dietOrder->delete();
        return redirect()->route('diet-orders.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'order_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'diet_type' => 'required|in:' . implode(',', array_keys(DietOrder::DIET_TYPES)),
            'texture' => 'nullable|string|max:100',
            'calories' => 'nullable|integer|min:0|max:10000',
            'restrictions' => 'nullable|array',
            'special_instructions' => 'nullable|string',
            'status' => 'nullable|in:active,paused,discontinued,completed',
        ]);
    }
}

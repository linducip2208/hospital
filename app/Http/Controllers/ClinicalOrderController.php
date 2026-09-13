<?php

namespace App\Http\Controllers;

use App\Models\ClinicalOrder;
use App\Models\Encounter;
use App\Services\DocumentNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicalOrderController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensurePermission('clinical_orders.manage');
        $orders = ClinicalOrder::with(['patient', 'encounter', 'orderingDoctor', 'destinationDepartment'])
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()->paginate(20)->withQueryString();
        return view('clinical-orders.index', compact('orders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensurePermission('clinical_orders.manage');
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'encounter_id' => 'required|exists:encounters,id',
            'ordering_doctor_id' => 'nullable|exists:doctors,id',
            'destination_department_id' => 'nullable|exists:departments,id',
            'order_type' => 'required|in:laboratory,radiology,procedure,pharmacy,diet,blood,consultation',
            'priority' => 'required|in:routine,urgent,cito',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'nullable|string|max:100',
            'items.*.item_id' => 'nullable|integer',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        $encounter = Encounter::findOrFail($data['encounter_id']);
        abort_unless($encounter->patient_id === (int) $data['patient_id'], 422, 'Pasien order tidak sama dengan pasien encounter.');
        $items = $data['items']; unset($data['items']);
        $data += ['order_no' => app(DocumentNumberService::class)->next('clinical_order', 'ORD'), 'status' => 'ordered', 'ordered_at' => now(), 'created_by' => auth()->id()];
        $order = ClinicalOrder::create($data);
        $order->items()->createMany($items);

        return redirect()->route('clinical-orders.show', $order)->with('success', 'Clinical order dikirim ke unit tujuan.');
    }

    public function show(ClinicalOrder $clinicalOrder): View
    {
        $this->ensurePermission('clinical_orders.manage');
        $clinicalOrder->load(['patient', 'encounter', 'orderingDoctor', 'destinationDepartment', 'items']);
        return view('clinical-orders.show', ['order' => $clinicalOrder]);
    }

    public function status(Request $request, ClinicalOrder $clinicalOrder): RedirectResponse
    {
        $data = $request->validate(['status' => 'required|in:accepted,in_progress,resulted,verified,completed,cancelled']);
        $changes = ['status' => $data['status']];
        if ($data['status'] === 'accepted') $changes['accepted_at'] = now();
        if ($data['status'] === 'completed') $changes['completed_at'] = now();
        $clinicalOrder->update($changes);
        return back()->with('success', 'Status clinical order diperbarui.');
    }
}

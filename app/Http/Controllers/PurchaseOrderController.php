<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\PurchaseOrderReceivingService;

class PurchaseOrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = PurchaseOrder::with('department');
        if ($search = $request->get('search')) {
            $query->whereAny(['po_number', 'supplier_name', 'notes'], 'like', "%{$search}%");
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($date = $request->get('date')) {
            $query->whereDate('order_date', $date);
        }
        $purchaseOrders = $query->latest()->paginate(15);
        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('purchase-orders.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'nullable|string|max:50',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date',
            'received_date' => 'nullable|date',
            'subtotal' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,submitted,approved,ordered,partially_received,received,closed,cancelled',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.item_name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.total_price' => 'nullable|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $items = $validated['items'] ?? [];
            unset($validated['items']);
            $po = PurchaseOrder::create($validated);
            foreach ($items as $item) {
                $item['purchase_order_id'] = $po->id;
                PurchaseOrderItem::create($item);
            }
            $po->recalculateTotals();
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil ditambahkan.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['department', 'items']);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $purchaseOrder->load('items');
        return view('purchase-orders.edit', compact('purchaseOrder', 'departments'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'nullable|string|max:50',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date',
            'received_date' => 'nullable|date',
            'subtotal' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,submitted,approved,ordered,partially_received,received,closed,cancelled',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.item_name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.total_price' => 'nullable|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $purchaseOrder) {
            $items = $validated['items'] ?? [];
            unset($validated['items']);
            $purchaseOrder->update($validated);
            $purchaseOrder->items()->delete();
            foreach ($items as $item) {
                $item['purchase_order_id'] = $purchaseOrder->id;
                PurchaseOrderItem::create($item);
            }
            $purchaseOrder->recalculateTotals();
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dihapus.');
    }

    public function receive(PurchaseOrder $purchaseOrder, PurchaseOrderReceivingService $service): RedirectResponse
    {
        $service->receive($purchaseOrder);
        return back()->with('success', 'PO diterima; batch dan stock movement berhasil dibuat.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\DrugSupplyOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DrugSupplyOrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = DrugSupplyOrder::with('items');
        if ($type = $request->get('order_type')) {
            $query->where('order_type', $type);
        }
        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('drug-supply-orders.index', [
            'orders' => $orders,
            'types' => DrugSupplyOrder::ORDER_TYPES,
        ]);
    }

    public function create(): View
    {
        return view('drug-supply-orders.create', [
            'drugs' => Drug::where('is_active', true)->orderBy('name')->get(),
            'types' => DrugSupplyOrder::ORDER_TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $order = DB::transaction(function () use ($validated) {
            $items = $validated['items'];
            unset($validated['items']);
            $validated['order_no'] = $this->generateNo($validated['order_type']);
            $validated['status'] ??= 'draft';
            $order = DrugSupplyOrder::create($validated);
            foreach ($items as $item) {
                $order->items()->create($item);
            }
            return $order;
        });
        return redirect()->route('drug-supply-orders.show', $order)->with('success', 'SP dibuat.');
    }

    public function show(DrugSupplyOrder $drugSupplyOrder): View
    {
        $drugSupplyOrder->load('items.drug');
        return view('drug-supply-orders.show', ['order' => $drugSupplyOrder]);
    }

    public function edit(DrugSupplyOrder $drugSupplyOrder): View
    {
        $drugSupplyOrder->load('items');
        return view('drug-supply-orders.edit', [
            'order' => $drugSupplyOrder,
            'drugs' => Drug::where('is_active', true)->orderBy('name')->get(),
            'types' => DrugSupplyOrder::ORDER_TYPES,
        ]);
    }

    public function update(Request $request, DrugSupplyOrder $drugSupplyOrder): RedirectResponse
    {
        $validated = $this->validateRequest($request, true);
        DB::transaction(function () use ($validated, $drugSupplyOrder) {
            $items = $validated['items'];
            unset($validated['items']);
            $drugSupplyOrder->update($validated);
            $drugSupplyOrder->items()->delete();
            foreach ($items as $item) {
                $drugSupplyOrder->items()->create($item);
            }
        });
        return redirect()->route('drug-supply-orders.show', $drugSupplyOrder)->with('success', 'SP diperbarui.');
    }

    public function destroy(DrugSupplyOrder $drugSupplyOrder): RedirectResponse
    {
        $drugSupplyOrder->delete();
        return redirect()->route('drug-supply-orders.index')->with('success', 'SP dihapus.');
    }

    public function print(DrugSupplyOrder $drugSupplyOrder): View
    {
        $drugSupplyOrder->load('items.drug');
        return view('drug-supply-orders.print', ['order' => $drugSupplyOrder]);
    }

    private function validateRequest(Request $request, bool $forUpdate = false): array
    {
        $rules = [
            'order_type' => 'required|in:' . implode(',', array_keys(DrugSupplyOrder::ORDER_TYPES)),
            'order_date' => 'required|date',
            'supplier_name' => 'required|string|max:255',
            'supplier_address' => 'nullable|string|max:500',
            'supplier_license_no' => 'nullable|string|max:100',
            'responsible_pharmacist' => 'required|string|max:255',
            'pharmacist_sipa_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_id' => 'nullable|exists:drugs,id',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.dose_form' => 'nullable|string|max:100',
            'items.*.strength' => 'nullable|string|max:100',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
        ];
        if ($forUpdate) {
            $rules['status'] = 'required|in:draft,sent,received,cancelled';
        }
        return $request->validate($rules);
    }

    private function generateNo(string $type): string
    {
        $prefix = match ($type) {
            'narcotic' => 'SPN',
            'psychotropic' => 'SPP',
            'precursor' => 'SPK',
            default => 'SP',
        };
        $count = DrugSupplyOrder::whereDate('created_at', today())->count() + 1;
        return sprintf('%s/%s/%04d', $prefix, now()->format('Ymd'), $count);
    }
}

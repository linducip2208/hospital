<?php

namespace App\Services;

use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseOrderReceivingService
{
    public function __construct(private JournalService $journal) {}

    public function receive(PurchaseOrder $purchaseOrder): PurchaseOrder
    {
        return DB::transaction(function () use ($purchaseOrder) {
            $po = PurchaseOrder::with('items')->lockForUpdate()->findOrFail($purchaseOrder->id);
            if (! in_array($po->status, ['ordered', 'partially_received'], true)) throw ValidationException::withMessages(['purchase_order' => 'PO harus berstatus ordered sebelum penerimaan.']);
            foreach ($po->items as $item) {
                if (! $item->drug_id) throw ValidationException::withMessages(['purchase_order' => "Item {$item->item_name} belum dipetakan ke master obat."]);
                $drug = Drug::lockForUpdate()->findOrFail($item->drug_id);
                $qty = (int) ($item->received_quantity ?: $item->quantity);
                if ($qty < 1) continue;
                $batch = DrugBatch::create(['drug_id' => $drug->id, 'batch_no' => 'PO-'.$po->id.'-'.$drug->id.'-'.now()->format('YmdHis'), 'lot_no' => $po->po_number, 'expiry_date' => now()->addYear(), 'quantity_received' => $qty, 'quantity_available' => $qty, 'purchase_price' => $item->unit_price, 'selling_price' => $drug->price]);
                $before = (int) $drug->stock; $drug->increment('stock', $qty); $item->update(['received_quantity' => $qty]);
                StockMovement::create(['drug_id' => $drug->id, 'drug_batch_id' => $batch->id, 'movement_type' => 'purchase', 'quantity' => $qty, 'stock_before' => $before, 'stock_after' => $before + $qty, 'reference_type' => PurchaseOrder::class, 'reference_id' => $po->id, 'created_by' => auth()->id(), 'notes' => 'Penerimaan '.$po->po_number]);
            }
            $po->update(['status' => 'received', 'received_date' => today(), 'received_by' => auth()->id()]);
            if ((float) $po->total_amount > 0) $this->journal->post('purchase_order', $po->id, 'Penerimaan persediaan '.$po->po_number, [['account_code' => ['1300', '1-006'], 'debit' => $po->total_amount], ['account_code' => ['2100', '2-001'], 'credit' => $po->total_amount]], $po->po_number);
            ActivityLogger::log('received', $po, 'Purchase order diterima dan stok bertambah.');
            return $po->refresh();
        });
    }
}

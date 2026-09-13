<?php

namespace App\Console\Commands;

use App\Models\Drug;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Services\DocumentNumberService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoReorderDrugs extends Command
{
    protected $signature = 'drugs:auto-reorder {--target=200 : Target stok setelah reorder}';

    protected $description = 'Buat draft Purchase Order otomatis untuk obat di bawah reorder level';

    public function handle(): int
    {
        $target = (int) $this->option('target');

        $lowStock = Drug::whereColumn('stock', '<', 'reorder_level')
            ->orderBy('vendor_id')
            ->get()
            ->groupBy('vendor_id');

        if ($lowStock->isEmpty()) {
            $this->info('Tidak ada obat yang perlu reorder.');

            return self::SUCCESS;
        }

        $created = 0;

        foreach ($lowStock as $vendorId => $drugs) {
            // Hindari duplikat: skip kalau sudah ada draft PO auto untuk vendor ini hari ini
            $exists = PurchaseOrder::where('auto_generated', true)
                ->where('vendor_id', $vendorId)
                ->whereDate('created_at', today())
                ->exists();

            if ($exists) {
                continue;
            }

            DB::transaction(function () use ($vendorId, $drugs, $target, &$created) {
                $po = PurchaseOrder::create([
                    'po_number' => app(DocumentNumberService::class)->next('purchase_order', 'PO', 4),
                    'vendor_id' => $vendorId ?: null,
                    'auto_generated' => true,
                    'supplier_name' => optional($drugs->first()->vendor)->name ?? 'Auto Reorder',
                    'order_date' => today(),
                    'status' => 'draft',
                    'notes' => 'Dibuat otomatis oleh sistem (stok di bawah reorder level).',
                    'subtotal' => 0,
                    'tax' => 0,
                    'total_amount' => 0,
                ]);

                $subtotal = 0;
                foreach ($drugs as $drug) {
                    $qty = max(1, $target - $drug->stock);
                    $price = (float) ($drug->price ?? 0);
                    $lineTotal = $qty * $price;
                    $subtotal += $lineTotal;

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'drug_id' => $drug->id,
                        'item_name' => $drug->name,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'total_price' => $lineTotal,
                    ]);
                }

                $po->update([
                    'subtotal' => $subtotal,
                    'total_amount' => $subtotal,
                ]);

                $created++;
            });
        }

        $this->info("Selesai. {$created} draft Purchase Order dibuat otomatis.");

        return self::SUCCESS;
    }
}

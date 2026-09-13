<?php

namespace App\Services;

use App\Models\Charge;
use App\Models\Dispensing;
use App\Models\Drug;
use App\Models\Prescription;
use App\Models\StockMovement;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;

class PharmacyDispensingService
{
    public function __construct(private DatabaseManager $database, private JournalService $journal)
    {
    }

    public function dispense(Prescription $prescription): Dispensing
    {
        return $this->database->transaction(function () use ($prescription) {
            $prescription = Prescription::with('items')->lockForUpdate()->findOrFail($prescription->id);
            if ($prescription->status === 'dispensed') {
                return $prescription->dispensings()->latest('id')->firstOrFail();
            }
            if (in_array($prescription->status, ['cancelled', 'draft'], true)) {
                throw ValidationException::withMessages(['prescription' => 'Resep harus berstatus issued sebelum diserahkan.']);
            }

            $dispensing = Dispensing::create([
                'dispensing_no' => app(DocumentNumberService::class)->next('dispensing', 'DSP', 4),
                'prescription_id' => $prescription->id,
                'patient_id' => $prescription->patient_id,
                'encounter_id' => $prescription->encounter_id,
                'pharmacist_id' => auth()->id(),
                'status' => 'dispensed',
                'dispensed_at' => now(),
            ]);
            $total = 0.0;

            foreach ($prescription->items as $item) {
                if (! $item->drug_id) {
                    throw ValidationException::withMessages(['prescription' => "Item {$item->drug_name} belum memiliki master obat."]);
                }
                $drug = Drug::lockForUpdate()->findOrFail($item->drug_id);
                $remaining = (int) $item->quantity;
                $batches = $drug->batches()
                    ->where('quantity_available', '>', 0)
                    ->whereDate('expiry_date', '>=', today())
                    ->orderBy('expiry_date')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();
                if ($batches->sum('quantity_available') < $remaining) {
                    throw ValidationException::withMessages(['prescription' => "Stok {$drug->name} tidak mencukupi atau batch telah kedaluwarsa."]);
                }

                foreach ($batches as $batch) {
                    if ($remaining <= 0) break;
                    $qty = min($remaining, (int) $batch->quantity_available);
                    $before = (int) $drug->stock;
                    $batch->decrement('quantity_available', $qty);
                    $drug->decrement('stock', $qty);
                    $price = (float) ($batch->selling_price ?: $drug->price);
                    $dispensing->items()->create([
                        'prescription_item_id' => $item->id,
                        'drug_id' => $drug->id,
                        'drug_batch_id' => $batch->id,
                        'quantity' => $qty,
                        'unit_price' => $price,
                    ]);
                    StockMovement::create([
                        'drug_id' => $drug->id,
                        'drug_batch_id' => $batch->id,
                        'movement_type' => 'dispense',
                        'quantity' => -$qty,
                        'stock_before' => $before,
                        'stock_after' => $before - $qty,
                        'reference_type' => Dispensing::class,
                        'reference_id' => $dispensing->id,
                        'created_by' => auth()->id(),
                        'notes' => 'Penyerahan resep '.$prescription->rx_no,
                    ]);
                    $total += $qty * $price;
                    $remaining -= $qty;
                }
            }

            Charge::firstOrCreate(
                ['source_type' => 'pharmacy', 'source_id' => $dispensing->id],
                [
                    'patient_id' => $prescription->patient_id,
                    'encounter_id' => $prescription->encounter_id,
                    'description' => 'Obat resep '.$prescription->rx_no,
                    'quantity' => 1,
                    'unit_price' => $total,
                    'amount' => $total,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                ]
            );
            $prescription->update(['status' => 'dispensed']);
            ActivityLogger::log('dispensed', $dispensing, 'Obat diserahkan dengan FEFO.');

            if ($total > 0) {
                $this->journal->post('dispensing', $dispensing->id, 'Pengakuan HPP farmasi '.$dispensing->dispensing_no, [
                    ['account_code' => ['5300', '5-002'], 'debit' => $total],
                    ['account_code' => ['1300', '1-006'], 'credit' => $total],
                ], $dispensing->dispensing_no);
            }

            return $dispensing->load('items');
        });
    }
}

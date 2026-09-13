<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispensingItem extends Model
{
    use HasFactory;

    protected $fillable = ['dispensing_id', 'prescription_item_id', 'drug_id', 'drug_batch_id', 'quantity', 'unit_price'];
    protected $casts = ['unit_price' => 'decimal:2'];

    public function dispensing(): BelongsTo { return $this->belongsTo(Dispensing::class); }
    public function prescriptionItem(): BelongsTo { return $this->belongsTo(PrescriptionItem::class); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
    public function batch(): BelongsTo { return $this->belongsTo(DrugBatch::class, 'drug_batch_id'); }
}

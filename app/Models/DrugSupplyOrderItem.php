<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrugSupplyOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'drug_supply_order_id', 'drug_id', 'drug_name',
        'dose_form', 'strength', 'quantity', 'unit',
    ];

    public function order(): BelongsTo { return $this->belongsTo(DrugSupplyOrder::class, 'drug_supply_order_id'); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
}

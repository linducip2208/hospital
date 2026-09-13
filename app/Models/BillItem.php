<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillItem extends Model
{
    use HasFactory;

    protected $fillable = ['bill_id', 'charge_id', 'description', 'quantity', 'unit_price', 'amount'];
    protected $casts = ['unit_price' => 'decimal:2', 'amount' => 'decimal:2'];

    public function bill(): BelongsTo { return $this->belongsTo(Bill::class); }
    public function charge(): BelongsTo { return $this->belongsTo(Charge::class); }
}

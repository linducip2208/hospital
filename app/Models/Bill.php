<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['bill_no', 'patient_id', 'encounter_id', 'subtotal', 'discount', 'tax', 'insurance_coverage', 'patient_responsibility', 'deposit', 'paid_amount', 'balance', 'status', 'issued_at', 'paid_at'];
    protected $casts = ['subtotal' => 'decimal:2', 'discount' => 'decimal:2', 'tax' => 'decimal:2', 'insurance_coverage' => 'decimal:2', 'patient_responsibility' => 'decimal:2', 'deposit' => 'decimal:2', 'paid_amount' => 'decimal:2', 'balance' => 'decimal:2', 'issued_at' => 'datetime', 'paid_at' => 'datetime'];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function items(): HasMany { return $this->hasMany(BillItem::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function paymentAllocations(): HasMany { return $this->hasMany(PaymentAllocation::class); }
}

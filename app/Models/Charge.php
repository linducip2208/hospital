<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'encounter_id', 'source_type', 'source_id', 'description', 'quantity', 'unit_price', 'amount', 'status', 'created_by'];
    protected $casts = ['unit_price' => 'decimal:2', 'amount' => 'decimal:2'];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function billItem(): \Illuminate\Database\Eloquent\Relations\HasOne { return $this->hasOne(BillItem::class); }
}

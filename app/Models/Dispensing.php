<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispensing extends Model
{
    use HasFactory;

    protected $fillable = ['dispensing_no', 'prescription_id', 'patient_id', 'encounter_id', 'pharmacist_id', 'status', 'dispensed_at'];
    protected $casts = ['dispensed_at' => 'datetime'];

    public function prescription(): BelongsTo { return $this->belongsTo(Prescription::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function pharmacist(): BelongsTo { return $this->belongsTo(User::class, 'pharmacist_id'); }
    public function items(): HasMany { return $this->hasMany(DispensingItem::class); }
}

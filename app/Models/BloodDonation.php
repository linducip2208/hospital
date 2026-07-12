<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodDonation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'donor_name', 'blood_type',
        'donation_date', 'expiry_date', 'quantity_ml',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'donation_date' => 'datetime',
            'expiry_date' => 'datetime',
            'quantity_ml' => 'integer',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}

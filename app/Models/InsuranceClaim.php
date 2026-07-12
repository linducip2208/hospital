<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceClaim extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_no', 'patient_id', 'payment_id',
        'insurance_provider', 'policy_number', 'claim_type',
        'service_date', 'claim_date', 'diagnosis_code', 'diagnosis_text',
        'claimed_amount', 'approved_amount', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'claim_date' => 'date',
            'claimed_amount' => 'decimal:2',
            'approved_amount' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function payment(): BelongsTo { return $this->belongsTo(Payment::class); }
}

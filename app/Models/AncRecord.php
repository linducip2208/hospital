<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AncRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'midwife_id', 'visit_date',
        'gestational_age', 'fundal_height', 'fetal_presentation',
        'fetal_heart_rate', 'blood_pressure_systolic', 'blood_pressure_diastolic',
        'weight', 'hemoglobin', 'urine_protein',
        'tt_immunization', 'iron_folate',
        'complications', 'next_visit_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'datetime',
            'next_visit_date' => 'datetime',
            'gestational_age' => 'integer',
            'fundal_height' => 'decimal:1',
            'fetal_heart_rate' => 'integer',
            'weight' => 'decimal:1',
            'hemoglobin' => 'decimal:1',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function midwife(): BelongsTo
    {
        return $this->belongsTo(User::class, 'midwife_id');
    }
}

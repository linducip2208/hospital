<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfectionSurveillance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'case_no', 'patient_id', 'infection_type',
        'detection_date', 'onset_date', 'site', 'organism',
        'symptoms', 'antibiotic_therapy', 'intervention', 'outcome',
    ];

    protected function casts(): array
    {
        return [
            'detection_date' => 'date',
            'onset_date' => 'date',
        ];
    }

    public const TYPES = [
        'vap' => 'VAP (Ventilator-Associated Pneumonia)',
        'clabsi' => 'CLABSI (Central Line)',
        'cauti' => 'CAUTI (Catheter-Associated UTI)',
        'ssi' => 'SSI (Surgical Site Infection)',
        'phlebitis' => 'Phlebitis',
        'decubitus' => 'Decubitus',
        'other' => 'Lainnya',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
}

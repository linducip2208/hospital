<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = ['encounter_id', 'medical_record_id', 'patient_id', 'doctor_id', 'icd10_code', 'diagnosis_name', 'diagnosis_type', 'is_confirmed', 'notes'];
    protected $casts = ['is_confirmed' => 'boolean'];

    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function medicalRecord(): BelongsTo { return $this->belongsTo(MedicalRecord::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
}

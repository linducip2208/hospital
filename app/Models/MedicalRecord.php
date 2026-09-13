<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_id', 'encounter_id', 'clinical_pathway_id',
        'diagnosis', 'icd10_code', 'icd10_name', 'action', 'medicine',
        'vital_signs', 'lab_results', 'notes', 'status', 'finalized_by', 'finalized_at',
    ];

    protected function casts(): array
    {
        return [
            'vital_signs' => 'json',
            'finalized_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }

    public function clinicalPathway(): BelongsTo
    {
        return $this->belongsTo(ClinicalPathway::class);
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class, 'medical_record_treatments')
            ->withPivot('quantity', 'unit_price', 'notes')
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function prescriptions(): HasMany { return $this->hasMany(Prescription::class); }
    public function labTests(): HasMany { return $this->hasMany(LabTest::class); }
    public function radiologies(): HasMany { return $this->hasMany(Radiology::class); }
    public function referrals(): HasMany { return $this->hasMany(Referral::class); }
    public function diagnoses(): HasMany { return $this->hasMany(Diagnosis::class); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encounter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['encounter_no', 'patient_id', 'appointment_id', 'polyclinic_id', 'doctor_id', 'room_id', 'hospital_bed_id', 'created_by', 'encounter_type', 'payer_type', 'status', 'started_at', 'ended_at'];
    protected $casts = ['started_at' => 'datetime', 'ended_at' => 'datetime'];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function polyclinic(): BelongsTo { return $this->belongsTo(Polyclinic::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function hospitalBed(): BelongsTo { return $this->belongsTo(HospitalBed::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function diagnoses(): HasMany { return $this->hasMany(Diagnosis::class); }
    public function clinicalOrders(): HasMany { return $this->hasMany(ClinicalOrder::class); }
    public function charges(): HasMany { return $this->hasMany(Charge::class); }
    public function bills(): HasMany { return $this->hasMany(Bill::class); }
    public function admissions(): HasMany { return $this->hasMany(Admission::class); }
    public function medicalRecords(): HasMany { return $this->hasMany(MedicalRecord::class); }
    public function prescriptions(): HasMany { return $this->hasMany(Prescription::class); }
    public function labTests(): HasMany { return $this->hasMany(LabTest::class); }
    public function radiologies(): HasMany { return $this->hasMany(Radiology::class); }
    public function vitalSignsRecords(): HasMany { return $this->hasMany(VitalSignsRecord::class); }
    public function nursingCares(): HasMany { return $this->hasMany(NursingCare::class); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rx_no', 'patient_id', 'doctor_id', 'medical_record_id', 'appointment_id', 'encounter_id',
        'prescribed_at', 'is_iter', 'iter_count', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'prescribed_at' => 'date',
            'is_iter' => 'boolean',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function medicalRecord(): BelongsTo { return $this->belongsTo(MedicalRecord::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function items(): HasMany { return $this->hasMany(PrescriptionItem::class); }
    public function dispensings(): HasMany { return $this->hasMany(Dispensing::class); }
}

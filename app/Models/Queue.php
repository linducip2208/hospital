<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = ['polyclinic_id', 'patient_id', 'doctor_id', 'appointment_id', 'encounter_id', 'queue_number', 'status', 'called_at', 'completed_at', 'notes'];

    protected function casts(): array
    {
        return ['called_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function polyclinic(): BelongsTo { return $this->belongsTo(Polyclinic::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
}

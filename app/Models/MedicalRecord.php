<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MedicalRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_id',
        'diagnosis', 'treatment_notes', 'prescription',
        'vital_signs', 'lab_results', 'follow_up',
        'action', 'medicine', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'vital_signs' => 'json',
        ];
    }

    /**
     * Map `action` field to `treatment_notes` in the database.
     */
    protected function action(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['treatment_notes'] ?? null,
            set: fn(string|null $value) => ['treatment_notes' => $value],
        );
    }

    /**
     * Map `medicine` field to `prescription` in the database.
     */
    protected function medicine(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['prescription'] ?? null,
            set: fn(string|null $value) => ['prescription' => $value],
        );
    }

    /**
     * Map `notes` field to `follow_up` in the database.
     */
    protected function notes(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['follow_up'] ?? null,
            set: fn(string|null $value) => ['follow_up' => $value],
        );
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
}

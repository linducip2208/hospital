<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surgery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'doctor_id', 'encounter_id', 'surgeon_id', 'assistant_id', 'anesthetist_id', 'name', 'description',
        'scheduled_date', 'status', 'operating_room', 'anesthesia', 'implants_materials', 'started_at', 'ended_at', 'outcome', 'complications', 'charge_amount', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'started_at' => 'datetime', 'ended_at' => 'datetime', 'charge_amount' => 'decimal:2',
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

    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function surgeon(): BelongsTo { return $this->belongsTo(Doctor::class, 'surgeon_id'); }
    public function assistant(): BelongsTo { return $this->belongsTo(Doctor::class, 'assistant_id'); }
    public function anesthetist(): BelongsTo { return $this->belongsTo(Doctor::class, 'anesthetist_id'); }
}

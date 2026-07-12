<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IcuMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'hospital_bed_id', 'recorded_at',
        'temperature', 'hr', 'rr', 'sbp', 'dbp', 'map',
        'spo2', 'gcs', 'cvp', 'ventilator', 'drips', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'temperature' => 'decimal:1',
            'cvp' => 'decimal:2',
            'ventilator' => 'json',
            'drips' => 'json',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function bed(): BelongsTo { return $this->belongsTo(HospitalBed::class, 'hospital_bed_id'); }
}

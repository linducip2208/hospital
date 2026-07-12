<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partograph extends Model
{
    use HasFactory;

    protected $fillable = [
        'maternity_id', 'recorded_at',
        'cervical_dilation', 'fetal_heart_rate', 'contractions_per_10min',
        'amniotic_fluid', 'moulding', 'oxytocin',
        'blood_pressure_systolic', 'blood_pressure_diastolic',
        'pulse', 'temperature', 'urine_output', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'cervical_dilation' => 'decimal:1',
            'fetal_heart_rate' => 'integer',
            'contractions_per_10min' => 'integer',
            'pulse' => 'integer',
            'temperature' => 'decimal:1',
            'urine_output' => 'integer',
        ];
    }

    public function maternity(): BelongsTo
    {
        return $this->belongsTo(Maternity::class);
    }
}

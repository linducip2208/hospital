<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeBlueActivation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code_no', 'patient_id', 'location',
        'activation_time', 'team_arrival_time', 'return_circulation_time', 'end_time',
        'outcome', 'team_leader', 'initial_rhythm',
        'interventions', 'medications_given', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'activation_time' => 'datetime',
            'team_arrival_time' => 'datetime',
            'return_circulation_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }

    public function getResponseTimeAttribute(): ?int
    {
        if (! $this->team_arrival_time || ! $this->activation_time) {
            return null;
        }
        return $this->activation_time->diffInSeconds($this->team_arrival_time);
    }
}

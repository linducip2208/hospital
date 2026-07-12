<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientScreening extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'screening_no', 'type', 'patient_id', 'user_id',
        'screened_at', 'answers', 'score', 'risk_level',
        'intervention', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'screened_at' => 'datetime',
            'answers' => 'json',
        ];
    }

    public const TYPES = [
        'fall_risk' => 'Skrining Risiko Jatuh (Morse)',
        'pain' => 'Skrining Nyeri',
        'nutrition' => 'Skrining Gizi (MST)',
        'pediatric_fall' => 'Skrining Risiko Jatuh Anak (Humpty Dumpty)',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostnatalRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'midwife_id', 'maternity_id', 'visit_date',
        'uterine_involution', 'lochia', 'perineum_wound',
        'breastfeeding', 'baby_weight', 'baby_condition',
        'complications', 'family_planning', 'next_visit_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'datetime',
            'next_visit_date' => 'datetime',
            'baby_weight' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function midwife(): BelongsTo
    {
        return $this->belongsTo(User::class, 'midwife_id');
    }

    public function maternity(): BelongsTo
    {
        return $this->belongsTo(Maternity::class);
    }
}

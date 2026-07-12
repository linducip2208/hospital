<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BabyImmunization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'maternity_id', 'vaccine_name', 'dose_number',
        'scheduled_date', 'administered_date', 'administered_by',
        'batch_number', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'administered_date' => 'datetime',
            'dose_number' => 'integer',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function maternity(): BelongsTo
    {
        return $this->belongsTo(Maternity::class);
    }
}

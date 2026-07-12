<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NursingCare extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'nurse_id', 'care_date',
        'subjective', 'objective', 'assessment',
        'plan', 'implementation', 'evaluation',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'care_date' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
}

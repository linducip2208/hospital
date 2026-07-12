<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicalPathway extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'diagnosis_code', 'diagnosis',
        'expected_los_days', 'phases',
        'inclusion_criteria', 'exclusion_criteria', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'phases' => 'json',
            'is_active' => 'boolean',
        ];
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }
}

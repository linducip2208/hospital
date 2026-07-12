<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DischargeSummary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'summary_no', 'patient_id', 'doctor_id', 'medical_record_id',
        'admission_date', 'discharge_date',
        'admission_diagnosis', 'discharge_diagnosis',
        'chief_complaint', 'history', 'physical_exam', 'investigations',
        'treatment', 'progress', 'discharge_medication', 'follow_up',
        'discharge_condition',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'datetime',
            'discharge_date' => 'datetime',
        ];
    }

    public const CONDITIONS = [
        'recovered' => 'Sembuh',
        'improved' => 'Membaik',
        'unchanged' => 'Tidak Ada Perubahan',
        'worsened' => 'Memburuk',
        'died' => 'Meninggal',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function medicalRecord(): BelongsTo { return $this->belongsTo(MedicalRecord::class); }
}

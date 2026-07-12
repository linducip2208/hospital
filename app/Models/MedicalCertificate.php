<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cert_no', 'type', 'patient_id', 'doctor_id', 'medical_record_id',
        'issue_date', 'rest_from', 'rest_until', 'rest_days',
        'diagnosis', 'purpose', 'exam_data', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'rest_from' => 'date',
            'rest_until' => 'date',
            'exam_data' => 'json',
        ];
    }

    public const TYPES = [
        'sick_leave' => 'Surat Keterangan Sakit',
        'healthy' => 'Surat Keterangan Sehat',
        'drug_free' => 'Surat Bebas Narkoba',
        'pregnancy' => 'Surat Keterangan Hamil',
        'not_pregnancy' => 'Surat Keterangan Tidak Hamil',
        'birth' => 'Surat Keterangan Lahir',
        'death' => 'Surat Keterangan Kematian',
        'visum' => 'Visum et Repertum',
        'color_blind_free' => 'Surat Bebas Buta Warna',
        'medical_check_up' => 'Surat Hasil MCU',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function medicalRecord(): BelongsTo { return $this->belongsTo(MedicalRecord::class); }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}

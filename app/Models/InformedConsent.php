<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformedConsent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'consent_no', 'kind', 'patient_id', 'doctor_id',
        'procedure_name', 'procedure_description', 'risks', 'alternatives',
        'signed_by_name', 'signed_by_relation', 'witness_name',
        'signed_at', 'notes',
    ];

    protected function casts(): array
    {
        return ['signed_at' => 'datetime'];
    }

    public const KINDS = [
        'consent' => 'Persetujuan Tindakan',
        'refusal' => 'Penolakan Tindakan',
        'aps' => 'Pulang Atas Permintaan Sendiri',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }

    public function getKindLabelAttribute(): string
    {
        return self::KINDS[$this->kind] ?? $this->kind;
    }
}

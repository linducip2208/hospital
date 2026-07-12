<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientSafetyIncident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'incident_no', 'incident_type', 'severity', 'patient_id', 'reporter_id',
        'occurred_at', 'reported_at', 'location', 'description',
        'immediate_action', 'root_cause', 'corrective_action', 'status',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'reported_at' => 'datetime',
        ];
    }

    public const TYPES = [
        'knc' => 'KNC (Kejadian Nyaris Cedera)',
        'ktc' => 'KTC (Kejadian Tidak Cedera)',
        'ktd' => 'KTD (Kejadian Tidak Diharapkan)',
        'kpc' => 'KPC (Kondisi Potensial Cedera)',
        'sentinel' => 'Sentinel Event',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function reporter(): BelongsTo { return $this->belongsTo(User::class, 'reporter_id'); }
}

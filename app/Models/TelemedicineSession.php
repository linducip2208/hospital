<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelemedicineSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session_no', 'patient_id', 'doctor_id',
        'scheduled_at', 'started_at', 'ended_at',
        'platform', 'meeting_url', 'meeting_id',
        'status', 'chief_complaint', 'assessment', 'plan', 'fee',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'fee' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
}

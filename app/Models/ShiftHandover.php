<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftHandover extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_nurse_id', 'to_nurse_id', 'shift_date', 'shift_type',
        'patient_summary', 'tasks_pending', 'incidents',
        'equipment_status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'shift_date' => 'datetime',
        ];
    }

    public function fromNurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_nurse_id');
    }

    public function toNurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_nurse_id');
    }
}

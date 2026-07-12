<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentCalibration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id', 'calibration_date', 'next_due_date', 'performed_by',
        'certificate_no', 'result', 'status', 'notes',
    ];

    protected $casts = [
        'calibration_date' => 'date',
        'next_due_date' => 'date',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function isOverdue(): bool
    {
        return $this->next_due_date && $this->next_due_date->isPast() && $this->status !== 'completed';
    }
}

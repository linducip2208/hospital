<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentMaintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id', 'scheduled_date', 'performed_date',
        'maintenance_type', 'performer', 'description',
        'findings', 'action', 'cost', 'result',
        'next_due_date', 'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'performed_date' => 'date',
            'next_due_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
}

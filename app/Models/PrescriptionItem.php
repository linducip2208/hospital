<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id', 'drug_id', 'drug_name', 'dose', 'frequency',
        'route', 'duration', 'quantity', 'unit', 'instructions',
        'is_compounded', 'is_high_alert',
    ];

    protected function casts(): array
    {
        return [
            'is_compounded' => 'boolean',
            'is_high_alert' => 'boolean',
        ];
    }

    public function prescription(): BelongsTo { return $this->belongsTo(Prescription::class); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
}

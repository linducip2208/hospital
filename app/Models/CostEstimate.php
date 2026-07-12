<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostEstimate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'estimate_no', 'patient_id', 'doctor_id', 'estimate_date',
        'procedure_name', 'total_amount', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimate_date' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function items(): HasMany { return $this->hasMany(CostEstimateItem::class); }
}

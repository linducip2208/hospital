<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostEstimateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cost_estimate_id', 'description', 'quantity', 'unit',
        'unit_price', 'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function estimate(): BelongsTo { return $this->belongsTo(CostEstimate::class, 'cost_estimate_id'); }
}

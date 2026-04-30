<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id', 'appointment_id', 'invoice_number',
        'subtotal', 'discount', 'tax', 'total',
        'paid_amount', 'change_amount', 'payment_method', 'status', 'notes',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    /**
     * Map `amount` to `total` for compatibility with views.
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['total'] ?? 0,
            set: fn(string|int|float $value) => ['total' => $value],
        );
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}

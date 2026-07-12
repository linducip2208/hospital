<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'po_number', 'department_id', 'supplier_name', 'supplier_phone',
        'order_date', 'expected_date', 'received_date',
        'subtotal', 'tax', 'total_amount', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'expected_date' => 'date',
            'received_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PurchaseOrder $po) {
            if (empty($po->po_number)) {
                $date = now()->format('Ymd');
                $last = static::where('po_number', 'like', "PO-{$date}-%")->latest('id')->first();
                $seq = $last ? (int) substr($last->po_number, -4) + 1 : 1;
                $po->po_number = 'PO-' . $date . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function recalculateTotals(): void
    {
        $this->load('items');
        $subtotal = $this->items->sum('total_price');
        $tax = (float) $this->tax;
        $this->updateQuietly([
            'subtotal' => $subtotal,
            'total_amount' => $subtotal + $tax,
        ]);
    }
}

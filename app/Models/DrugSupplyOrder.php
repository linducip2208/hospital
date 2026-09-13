<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrugSupplyOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_no', 'order_type', 'order_date', 'supplier_name',
        'supplier_address', 'supplier_license_no',
        'responsible_pharmacist', 'pharmacist_sipa_no', 'created_by', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['order_date' => 'date'];
    }

    public const ORDER_TYPES = [
        'regular' => 'SP Reguler',
        'narcotic' => 'SP Narkotika',
        'psychotropic' => 'SP Psikotropika',
        'precursor' => 'SP Prekursor',
    ];

    public function items(): HasMany { return $this->hasMany(DrugSupplyOrderItem::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function getOrderTypeLabelAttribute(): string
    {
        return self::ORDER_TYPES[$this->order_type] ?? $this->order_type;
    }
}

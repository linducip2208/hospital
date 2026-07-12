<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'category', 'contact_person', 'phone', 'email',
        'address', 'is_active', 'notes',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function drugs(): HasMany
    {
        return $this->hasMany(Drug::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}

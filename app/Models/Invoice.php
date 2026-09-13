<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['bill_id', 'invoice_number', 'status', 'issued_at'];
    protected $casts = ['issued_at' => 'datetime'];

    public function bill(): BelongsTo { return $this->belongsTo(Bill::class); }
}

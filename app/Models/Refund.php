<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = ['payment_id', 'bill_id', 'amount', 'reason', 'status', 'processed_by', 'processed_at'];
    protected $casts = ['amount' => 'decimal:2', 'processed_at' => 'datetime'];

    public function payment(): BelongsTo { return $this->belongsTo(Payment::class); }
    public function bill(): BelongsTo { return $this->belongsTo(Bill::class); }
    public function processor(): BelongsTo { return $this->belongsTo(User::class, 'processed_by'); }
}

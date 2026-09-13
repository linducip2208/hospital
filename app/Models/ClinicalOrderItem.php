<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['clinical_order_id', 'item_type', 'item_id', 'description', 'quantity', 'result_text', 'result_data', 'reference_range', 'unit', 'abnormal_flag', 'critical_flag', 'verified_by', 'verified_at'];
    protected $casts = ['result_data' => 'array', 'critical_flag' => 'boolean', 'verified_at' => 'datetime'];

    public function order(): BelongsTo { return $this->belongsTo(ClinicalOrder::class, 'clinical_order_id'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
}

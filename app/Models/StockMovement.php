<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = ['drug_id', 'drug_batch_id', 'movement_type', 'quantity', 'stock_before', 'stock_after', 'reference_type', 'reference_id', 'created_by', 'notes'];

    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
    public function batch(): BelongsTo { return $this->belongsTo(DrugBatch::class, 'drug_batch_id'); }
    public function reference(): MorphTo { return $this->morphTo(); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}

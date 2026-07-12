<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrugDestructionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'drug_destruction_id', 'drug_id', 'drug_name',
        'batch_no', 'expired_at', 'quantity', 'unit', 'reason',
    ];

    protected function casts(): array
    {
        return ['expired_at' => 'date'];
    }

    public function destruction(): BelongsTo { return $this->belongsTo(DrugDestruction::class, 'drug_destruction_id'); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
}

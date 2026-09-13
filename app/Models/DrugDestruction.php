<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrugDestruction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'destruction_no', 'destruction_date', 'location', 'method',
        'responsible_pharmacist', 'created_by', 'witnessed_by',
        'witness_name_1', 'witness_name_2', 'witness_role_1', 'witness_role_2',
        'reason',
    ];

    protected function casts(): array
    {
        return ['destruction_date' => 'date'];
    }

    public function items(): HasMany { return $this->hasMany(DrugDestructionItem::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function witness(): BelongsTo { return $this->belongsTo(User::class, 'witnessed_by'); }
}

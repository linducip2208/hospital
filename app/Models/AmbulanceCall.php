<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmbulanceCall extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ambulance_id', 'patient_name', 'pickup_location', 'destination',
        'call_date', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'call_date' => 'datetime',
        ];
    }

    public function ambulance(): BelongsTo
    {
        return $this->belongsTo(Ambulance::class);
    }
}

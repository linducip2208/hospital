<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ambulance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_number', 'model', 'type', 'status',
        'driver_name', 'driver_phone', 'notes',
    ];

    public function calls(): HasMany
    {
        return $this->hasMany(AmbulanceCall::class);
    }
}

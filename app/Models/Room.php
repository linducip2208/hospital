<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_number', 'room_type', 'floor', 'bed_count',
        'price_per_day', 'facilities', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'bed_count' => 'integer',
            'floor' => 'integer',
            'price_per_day' => 'decimal:2',
            'facilities' => 'json',
        ];
    }
}

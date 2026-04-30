<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Treatment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'category', 'price',
        'duration_minutes', 'notes', 'requirements', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_minutes' => 'integer',
            'requirements' => 'json',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Treatment $treatment) {
            if (empty($treatment->slug)) {
                $treatment->slug = Str::slug($treatment->name);
            }
        });
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}

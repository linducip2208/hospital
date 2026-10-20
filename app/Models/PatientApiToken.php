<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientApiToken extends Model
{
    protected $fillable = ['patient_id', 'name', 'token_hash', 'expires_at', 'last_used_at'];

    protected $casts = ['expires_at' => 'datetime', 'last_used_at' => 'datetime'];

    protected $hidden = ['token_hash'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}

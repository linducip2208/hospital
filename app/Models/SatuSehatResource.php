<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuSehatResource extends Model
{
    use HasFactory;

    protected $fillable = ['local_type', 'local_id', 'resource_type', 'resource_id', 'sync_status', 'payload_hash', 'last_payload', 'last_synced_at', 'last_error', 'attempts'];
    protected $casts = ['last_payload' => 'array', 'last_synced_at' => 'datetime'];
}

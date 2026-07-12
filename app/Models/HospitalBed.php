<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalBed extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_id', 'bed_code', 'label', 'status',
        'current_patient_id', 'occupied_since', 'notes',
    ];

    protected function casts(): array
    {
        return ['occupied_since' => 'datetime'];
    }

    public const STATUSES = [
        'available' => 'Tersedia',
        'occupied' => 'Terisi',
        'reserved' => 'Reservasi',
        'cleaning' => 'Pembersihan',
        'maintenance' => 'Maintenance',
        'blocked' => 'Tidak Tersedia',
    ];

    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function currentPatient(): BelongsTo { return $this->belongsTo(Patient::class, 'current_patient_id'); }
    public function icuMonitorings(): HasMany { return $this->hasMany(IcuMonitoring::class); }
}

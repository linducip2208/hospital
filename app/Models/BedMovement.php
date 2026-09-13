<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BedMovement extends Model
{
    use HasFactory;

    protected $fillable = ['admission_id', 'room_id', 'hospital_bed_id', 'moved_at', 'moved_by', 'reason'];
    protected $casts = ['moved_at' => 'datetime'];

    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function hospitalBed(): BelongsTo { return $this->belongsTo(HospitalBed::class); }
    public function movedBy(): BelongsTo { return $this->belongsTo(User::class, 'moved_by'); }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emergency extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['patient_id','doctor_id','triage','arrival_mode','complaint','diagnosis','action_taken','status','discharge_date','notes'];
    protected function casts(): array { return ['discharge_date'=>'datetime']; }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
}

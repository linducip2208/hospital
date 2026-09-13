<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emergency extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['patient_id','doctor_id','encounter_id','triage','arrival_mode','complaint','diagnosis','action_taken','status','discharge_date','arrived_at','triaged_at','treatment_started_at','disposition_at','notes'];
    protected function casts(): array { return ['discharge_date'=>'datetime', 'arrived_at'=>'datetime', 'triaged_at'=>'datetime', 'treatment_started_at'=>'datetime', 'disposition_at'=>'datetime']; }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
}

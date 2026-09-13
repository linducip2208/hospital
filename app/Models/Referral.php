<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['patient_id','from_polyclinic_id','to_polyclinic_id','doctor_id','medical_record_id','encounter_id','reason','diagnosis','status','notes'];
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function fromPolyclinic(): BelongsTo { return $this->belongsTo(Polyclinic::class, 'from_polyclinic_id'); }
    public function toPolyclinic(): BelongsTo { return $this->belongsTo(Polyclinic::class, 'to_polyclinic_id'); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function medicalRecord(): BelongsTo { return $this->belongsTo(MedicalRecord::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
}

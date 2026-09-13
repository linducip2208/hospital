<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Radiology extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['patient_id','doctor_id','appointment_id','medical_record_id','encounter_id','clinical_order_id','examination_name','body_part','modality','radiologist_id','status','findings','verified_by','verified_at','result_status','pacs_reference_url','notes'];
    protected function casts(): array { return ['verified_at' => 'datetime']; }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function medicalRecord(): BelongsTo { return $this->belongsTo(MedicalRecord::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function clinicalOrder(): BelongsTo { return $this->belongsTo(ClinicalOrder::class); }
    public function radiologist(): BelongsTo { return $this->belongsTo(Doctor::class, 'radiologist_id'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
}

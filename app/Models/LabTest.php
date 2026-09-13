<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTest extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['patient_id','doctor_id','appointment_id','medical_record_id','encounter_id','clinical_order_id','test_name','test_type','sample_type','status','results','result_date','notes','accession_no','specimen','collected_at','collected_by','verified_by','verified_at','reference_range','unit','abnormal_flag','critical_flag','result_status'];
    protected function casts(): array
    {
        return ['result_date' => 'datetime', 'collected_at' => 'datetime', 'verified_at' => 'datetime', 'critical_flag' => 'boolean'];
    }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function medicalRecord(): BelongsTo { return $this->belongsTo(MedicalRecord::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function clinicalOrder(): BelongsTo { return $this->belongsTo(ClinicalOrder::class); }
    public function collector(): BelongsTo { return $this->belongsTo(User::class, 'collected_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
}

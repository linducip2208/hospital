<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maternity extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['patient_id','doctor_id','admission_date','delivery_date','delivery_type','baby_gender','baby_weight','baby_length','baby_name','complications','status','notes'];
    protected function casts(): array
    {
        return ['admission_date'=>'datetime','delivery_date'=>'datetime','baby_weight'=>'decimal:2','baby_length'=>'decimal:1'];
    }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
}

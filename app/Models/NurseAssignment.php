<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NurseAssignment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','patient_id','assignment_type','task_description','shift','status','notes'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
}

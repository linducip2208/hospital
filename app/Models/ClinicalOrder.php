<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicalOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order_no', 'patient_id', 'encounter_id', 'ordering_doctor_id', 'destination_department_id', 'created_by', 'order_type', 'priority', 'status', 'ordered_at', 'accepted_at', 'completed_at'];
    protected $casts = ['ordered_at' => 'datetime', 'accepted_at' => 'datetime', 'completed_at' => 'datetime'];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function orderingDoctor(): BelongsTo { return $this->belongsTo(Doctor::class, 'ordering_doctor_id'); }
    public function destinationDepartment(): BelongsTo { return $this->belongsTo(Department::class, 'destination_department_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function items(): HasMany { return $this->hasMany(ClinicalOrderItem::class); }
}

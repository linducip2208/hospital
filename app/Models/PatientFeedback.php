<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientFeedback extends Model
{
    use HasFactory, SoftDeletes;

    // Laravel default pluralization treats "feedback" as already-plural ("feedback"),
    // tapi tabel sebenarnya `patient_feedbacks`. Explicit set untuk pastikan.
    protected $table = 'patient_feedbacks';

    protected $fillable = [
        'patient_id', 'visit_date', 'service_type',
        'rating_overall', 'rating_doctor', 'rating_nurse',
        'rating_facility', 'rating_cleanliness', 'rating_speed',
        'would_recommend', 'positive', 'negative', 'suggestion',
        'is_anonymous', 'respondent_name', 'respondent_contact', 'status',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'would_recommend' => 'boolean',
            'is_anonymous' => 'boolean',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
}

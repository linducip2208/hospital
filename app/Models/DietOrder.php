<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DietOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_no', 'patient_id', 'doctor_id',
        'order_date', 'start_date', 'end_date',
        'diet_type', 'texture', 'calories', 'restrictions',
        'special_instructions', 'status',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'restrictions' => 'json',
        ];
    }

    public const DIET_TYPES = [
        'regular' => 'Diet Biasa',
        'soft' => 'Diet Lunak',
        'liquid' => 'Diet Cair',
        'puree' => 'Diet Saring',
        'tube_feed' => 'Diet via Sonde',
        'parenteral' => 'Parenteral',
        'diabetic' => 'Diet DM',
        'low_salt' => 'Rendah Garam',
        'low_protein' => 'Rendah Protein',
        'high_protein' => 'Tinggi Protein',
        'low_fat' => 'Rendah Lemak',
        'gluten_free' => 'Bebas Gluten',
        'custom' => 'Khusus',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalWaste extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'manifest_no', 'waste_type', 'weight_kg', 'department_id',
        'collection_date', 'disposal_date', 'transporter', 'vendor_id',
        'status', 'handled_by', 'notes',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'collection_date' => 'date',
        'disposal_date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public static function typeLabels(): array
    {
        return [
            'infectious' => 'Infeksius',
            'sharps' => 'Benda Tajam',
            'pharmaceutical' => 'Farmasi',
            'chemical' => 'Kimia',
            'radioactive' => 'Radioaktif',
            'pathological' => 'Patologis',
            'general' => 'Umum',
        ];
    }
}

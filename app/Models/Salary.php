<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id', 'user_id', 'period_month', 'period_year',
        'base_salary', 'overtime_hours', 'overtime_pay', 'bonus',
        'deduction', 'deduction_note', 'total_salary', 'status',
        'paid_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'overtime_hours' => 'decimal:1',
            'overtime_pay' => 'decimal:2',
            'bonus' => 'decimal:2',
            'deduction' => 'decimal:2',
            'total_salary' => 'decimal:2',
            'paid_at' => 'datetime',
            'period_month' => 'integer',
            'period_year' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

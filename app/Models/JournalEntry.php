<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'journal_number', 'entry_date', 'description', 'reference',
        'total_debit', 'total_credit', 'status', 'posted_by',
        'posted_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'posted_at' => 'datetime',
            'total_debit' => 'decimal:2',
            'total_credit' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (JournalEntry $je) {
            if (empty($je->journal_number)) {
                $date = now()->format('Ymd');
                $last = static::where('journal_number', 'like', "JRN-{$date}-%")->latest('id')->first();
                $seq = $last ? (int) substr($last->journal_number, -4) + 1 : 1;
                $je->journal_number = 'JRN-' . $date . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}

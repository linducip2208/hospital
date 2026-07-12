<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'email', 'password', 'phone', 'nik', 'bpjs_number', 'nik_verified',
        'birth_date', 'gender', 'address', 'blood_type', 'allergies', 'medical_history',
        'emergency_contact_name', 'emergency_contact_phone', 'notes', 'is_active',
        'portal_last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
            'nik_verified' => 'boolean',
            'password' => 'hashed',
            'portal_last_login_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class);
    }

    public function radiologies(): HasMany
    {
        return $this->hasMany(Radiology::class);
    }

    public function maternities(): HasMany
    {
        return $this->hasMany(Maternity::class);
    }

    public function emergencies(): HasMany
    {
        return $this->hasMany(Emergency::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }
}

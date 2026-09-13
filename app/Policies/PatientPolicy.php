<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('patients.view'); }
    public function view(User $user, Patient $patient): bool
    {
        if (! $user->hasPermission('patients.view')) return false;
        if (in_array($user->role, ['admin', 'developer', 'director', 'staff', 'cashier', 'pharmacist', 'lab_technician'], true)) return true;
        if ($user->role === 'doctor') return $patient->encounters()->where('doctor_id', $user->doctor?->id)->exists();
        return in_array($user->role, ['nurse', 'midwife'], true) && $patient->appointments()->exists();
    }
    public function create(User $user): bool { return $user->hasPermission('patients.create'); }
    public function update(User $user, Patient $patient): bool { return $user->hasPermission('patients.update'); }
    public function delete(User $user, Patient $patient): bool { return in_array($user->role, ['admin', 'developer'], true); }
}

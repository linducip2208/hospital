<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\User;

class MedicalRecordPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('medical_records.view'); }
    public function view(User $user, MedicalRecord $record): bool
    {
        if (! $user->hasPermission('medical_records.view')) return false;
        if (in_array($user->role, ['admin', 'developer', 'director'], true)) return true;
        if ($user->role === 'doctor') return $record->doctor?->user_id === $user->id || $record->encounter?->doctor?->user_id === $user->id;
        return in_array($user->role, ['nurse', 'midwife'], true);
    }
    public function create(User $user): bool { return $user->hasPermission('medical_records.create'); }
    public function update(User $user, MedicalRecord $record): bool { return $user->hasPermission('medical_records.create') && $record->status !== 'finalized'; }
    public function finalize(User $user, MedicalRecord $record): bool { return $user->hasPermission('medical_records.sign') && $record->status !== 'finalized'; }
}

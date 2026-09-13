<?php

namespace App\Jobs;

use App\Models\Patient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPatientToSatuSehat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(public Patient $patient) {}
    public function handle(): void
    {
        SyncSatuSehatResource::dispatch('Patient', $this->patient->id, 'Patient', ['resourceType' => 'Patient', 'identifier' => [['system' => 'urn:hospital:mrn', 'value' => (string) ($this->patient->mr_number ?? $this->patient->id)]], 'name' => [['text' => $this->patient->name]], 'gender' => $this->patient->gender === 'male' ? 'male' : ($this->patient->gender === 'female' ? 'female' : 'unknown'), 'birthDate' => $this->patient->birth_date?->format('Y-m-d')]);
    }
}

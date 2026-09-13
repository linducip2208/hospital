<?php

namespace App\Jobs;

use App\Models\Encounter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncEncounterToSatuSehat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(public Encounter $encounter) {}
    public function handle(): void
    {
        SyncSatuSehatResource::dispatch('Encounter', $this->encounter->id, 'Encounter', ['resourceType' => 'Encounter', 'status' => $this->encounter->status === 'completed' ? 'finished' : 'in-progress', 'class' => ['code' => $this->encounter->encounter_type], 'identifier' => [['value' => $this->encounter->encounter_no]]]);
    }
}

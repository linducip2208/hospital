<?php

namespace App\Jobs;

use App\Models\SatuSehatResource;
use App\Services\SatuSehatClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncSatuSehatResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    public function __construct(public string $localType, public int $localId, public string $resourceType, public array $payload) {}
    public function backoff(): array { return [60, 300, 900]; }

    public function handle(SatuSehatClient $client): void
    {
        $mapping = SatuSehatResource::updateOrCreate(
            ['local_type' => $this->localType, 'local_id' => $this->localId, 'resource_type' => $this->resourceType],
            ['sync_status' => 'pending', 'payload_hash' => hash('sha256', json_encode($this->payload)), 'last_payload' => $this->payload]
        );
        if (! $client->isConfigured()) {
            $mapping->update(['sync_status' => 'skipped', 'last_error' => 'SatuSehat belum dikonfigurasi; tidak ada panggilan eksternal.']);
            return;
        }
        $mapping->increment('attempts');
        $response = $client->post($this->resourceType, $this->payload);
        if (! $response || empty($response['id'])) {
            $mapping->update(['sync_status' => 'failed', 'last_error' => 'Respons SatuSehat tidak valid atau request gagal.']);
            return;
        }
        $mapping->update(['resource_id' => $response['id'], 'sync_status' => 'synced', 'last_synced_at' => now(), 'last_error' => null]);
    }
}

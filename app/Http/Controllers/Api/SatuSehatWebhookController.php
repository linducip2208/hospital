<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\SatuSehatClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SatuSehatWebhookController extends Controller
{
    public function receive(Request $request): JsonResponse
    {
        $client = new SatuSehatClient;

        if (! $client->isWebhookEnabled()) {
            return response()->json(['error' => 'Webhook not enabled'], 404);
        }

        $payload = $request->getContent();
        $signature = $request->header('X-Satusehat-Signature') ?? '';

        if (empty($signature) || ! $client->verifyWebhookSignature($payload, $signature)) {
            Log::warning('SatuSehat webhook received with invalid signature', [
                'ip' => $request->ip(),
                'signature_present' => ! empty($signature),
            ]);

            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $data = $request->all();
        $resourceType = $data['resourceType'] ?? 'unknown';
        $resourceId = $data['id'] ?? 'unknown';

        Log::info('SatuSehat webhook received', [
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'method' => $request->method(),
        ]);

        match ($resourceType) {
            'Patient' => $this->handlePatient($data),
            'Encounter' => $this->handleEncounter($data),
            'Observation' => $this->handleObservation($data),
            'Condition' => $this->handleCondition($data),
            'Procedure' => $this->handleProcedure($data),
            'MedicationRequest' => $this->handleMedicationRequest($data),
            'DiagnosticReport' => $this->handleDiagnosticReport($data),
            'Composition' => $this->handleComposition($data),
            'Organization' => $this->handleOrganization($data),
            'Practitioner' => $this->handlePractitioner($data),
            'Subscription' => $this->handleSubscription($data),
            default => Log::info('SatuSehat webhook unhandled resource type', ['type' => $resourceType]),
        };

        return response()->json(['ok' => true]);
    }

    private function handlePatient(array $data): void
    {
        $nik = null;
        $ihi = $data['id'] ?? null;

        foreach ($data['identifier'] ?? [] as $id) {
            if (($id['system'] ?? '') === 'https://fhir.kemkes.go.id/id/nik') {
                $nik = $id['value'] ?? null;
                break;
            }
        }

        $name = '';
        foreach ($data['name'] ?? [] as $n) {
            if (($n['use'] ?? '') === 'official') {
                $name = implode(' ', $n['given'] ?? []).' '.($n['family'] ?? '');
                break;
            }
        }
        if (empty($name) && ! empty($data['name'])) {
            $n = $data['name'][0];
            $name = implode(' ', $n['given'] ?? []).' '.($n['family'] ?? '');
        }

        Log::info('SatuSehat webhook — Patient sync', [
            'ihi' => $ihi,
            'identifier_present' => ! empty($nik),
        ]);

        if ($nik) {
            Patient::where('nik', $nik)->update(['satusehat_id' => $ihi]);
        }
    }

    private function handleEncounter(array $data): void
    {
        Log::info('SatuSehat webhook — Encounter received', ['id' => $data['id'] ?? null]);
    }

    private function handleObservation(array $data): void
    {
        Log::info('SatuSehat webhook — Observation received', ['id' => $data['id'] ?? null]);
    }

    private function handleCondition(array $data): void
    {
        Log::info('SatuSehat webhook — Condition received', ['id' => $data['id'] ?? null]);
    }

    private function handleProcedure(array $data): void
    {
        Log::info('SatuSehat webhook — Procedure received', ['id' => $data['id'] ?? null]);
    }

    private function handleMedicationRequest(array $data): void
    {
        Log::info('SatuSehat webhook — MedicationRequest received', ['id' => $data['id'] ?? null]);
    }

    private function handleDiagnosticReport(array $data): void
    {
        Log::info('SatuSehat webhook — DiagnosticReport received', ['id' => $data['id'] ?? null]);
    }

    private function handleComposition(array $data): void
    {
        Log::info('SatuSehat webhook — Composition received', ['id' => $data['id'] ?? null]);
    }

    private function handleOrganization(array $data): void
    {
        Log::info('SatuSehat webhook — Organization received', ['id' => $data['id'] ?? null]);
    }

    private function handlePractitioner(array $data): void
    {
        Log::info('SatuSehat webhook — Practitioner received', ['id' => $data['id'] ?? null]);
    }

    private function handleSubscription(array $data): void
    {
        Log::info('SatuSehat webhook — Subscription received', ['id' => $data['id'] ?? null]);
    }
}

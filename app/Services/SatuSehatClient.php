<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SatuSehatClient
{
    private const TOKEN_CACHE_KEY = 'satusehat:access_token';
    private const TOKEN_EXPIRY_BUFFER = 60;

    private ?string $baseUrl = null;
    private ?string $clientId = null;
    private ?string $clientSecret = null;
    private ?string $organizationId = null;
    private bool $isEnabled = false;
    private ?string $webhookUrl = null;
    private ?string $webhookSecret = null;
    private bool $webhookEnabled = false;

    public function __construct()
    {
        $this->loadConfig();
    }

    private function loadConfig(): void
    {
        $settings = Setting::where('group', 'satu_sehat')->get()->pluck('value', 'key');

        $this->baseUrl = rtrim($settings['satusehat_base_url'] ?? '', '/');
        $this->organizationId = $settings['satusehat_organization_id'] ?? null;
        $this->isEnabled = (bool) ($settings['satusehat_is_enabled'] ?? false);
        $this->webhookUrl = $settings['satusehat_webhook_url'] ?? null;
        $this->webhookEnabled = (bool) ($settings['satusehat_webhook_enabled'] ?? false);

        foreach (['satusehat_client_id', 'satusehat_client_secret', 'satusehat_webhook_secret'] as $key) {
            if (! empty($settings[$key])) {
                try {
                    $settings[$key] = Crypt::decryptString($settings[$key]);
                } catch (\Illuminate\Contracts\Encryption\DecryptException) {
                    $settings[$key] = null;
                }
            }
        }

        $this->clientId = $settings['satusehat_client_id'] ?? null;
        $this->clientSecret = $settings['satusehat_client_secret'] ?? null;
        $this->webhookSecret = $settings['satusehat_webhook_secret'] ?? null;
    }

    public function isConfigured(): bool
    {
        return $this->isEnabled
            && ! empty($this->baseUrl)
            && ! empty($this->clientId)
            && ! empty($this->clientSecret)
            && ! empty($this->organizationId);
    }

    public function testConnection(): array
    {
        if (! $this->isConfigured()) {
            return [
                'ok' => false,
                'error' => 'Konfigurasi Satu Sehat belum lengkap. Isi semua kredensial dan aktifkan integrasi.',
            ];
        }

        try {
            $token = $this->getToken();

            if ($token === null) {
                return [
                    'ok' => false,
                    'error' => 'Gagal mendapatkan access token. Periksa Client ID dan Client Secret.',
                ];
            }

            $response = Http::timeout(15)
                ->withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->accept('application/json')
                ->get($this->fhirUrl('/Organization/' . $this->organizationId));

            if ($response->successful()) {
                $body = $response->json();
                $orgName = $body['name'] ?? ($body['resourceType'] === 'Organization' ? 'OK' : 'Terhubung');

                return [
                    'ok' => true,
                    'message' => 'Koneksi ke Satu Sehat berhasil. Organisasi: ' . $orgName,
                    'organization_name' => $body['name'] ?? null,
                ];
            }

            if ($response->status() === 401 || $response->status() === 403) {
                $this->clearToken();

                return [
                    'ok' => false,
                    'error' => 'Token tidak valid atau tidak memiliki akses (HTTP ' . $response->status() . ').',
                ];
            }

            return [
                'ok' => false,
                'error' => 'Server Satu Sehat merespon dengan HTTP ' . $response->status() . '.',
            ];
        } catch (\Throwable $e) {
            Log::error('SatuSehat connection test failed: ' . $e->getMessage());

            return [
                'ok' => false,
                'error' => 'Gagal terhubung ke Satu Sehat: ' . $e->getMessage(),
            ];
        }
    }

    public function getToken(): ?string
    {
        $cached = Cache::get(self::TOKEN_CACHE_KEY);
        if ($cached) {
            return $cached;
        }

        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
                ->asForm()
                ->post($this->baseUrl . '/oauth2/v1/accesstoken', [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'client_credentials',
                ]);

            if (! $response->successful()) {
                Log::error('SatuSehat token request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();

            $token = $data['access_token'] ?? null;
            if (! $token) {
                Log::error('SatuSehat token response missing access_token', ['response' => $data]);

                return null;
            }

            $expiresIn = (int) ($data['expires_in'] ?? 3600);
            $ttl = max(60, $expiresIn - self::TOKEN_EXPIRY_BUFFER);

            Cache::put(self::TOKEN_CACHE_KEY, $token, now()->addSeconds($ttl));

            return $token;
        } catch (\Throwable $e) {
            Log::error('SatuSehat token exception: ' . $e->getMessage());

            return null;
        }
    }

    public function clearToken(): void
    {
        Cache::forget(self::TOKEN_CACHE_KEY);
    }

    public function get(string $path, array $query = []): ?array
    {
        return $this->request('GET', $path, $query);
    }

    public function post(string $path, array $data = []): ?array
    {
        return $this->request('POST', $path, [], $data);
    }

    public function put(string $path, array $data = []): ?array
    {
        return $this->request('PUT', $path, [], $data);
    }

    public function delete(string $path): ?array
    {
        return $this->request('DELETE', $path);
    }

    private function request(string $method, string $path, array $query = [], ?array $body = null): ?array
    {
        $token = $this->getToken();
        if (! $token) {
            Log::warning('SatuSehat request aborted — no token available', ['method' => $method, 'path' => $path]);

            return null;
        }

        $url = str_starts_with($path, 'http') ? $path : $this->fhirUrl($path);

        try {
            $response = match (strtoupper($method)) {
                'GET' => Http::timeout(30)
                    ->withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->accept('application/json')
                    ->get($url, $query),
                'POST' => Http::timeout(30)
                    ->withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->accept('application/json')
                    ->post($url, $body ?? []),
                'PUT' => Http::timeout(30)
                    ->withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->accept('application/json')
                    ->put($url, $body ?? []),
                'DELETE' => Http::timeout(30)
                    ->withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->accept('application/json')
                    ->delete($url),
                default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
            };

            if ($response->status() === 401) {
                $this->clearToken();
                $token = $this->getToken();
                if (! $token) {
                    return null;
                }

                $response = match (strtoupper($method)) {
                    'GET' => Http::timeout(30)
                        ->withToken($token)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->accept('application/json')
                        ->get($url, $query),
                    'POST' => Http::timeout(30)
                        ->withToken($token)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->accept('application/json')
                        ->post($url, $body ?? []),
                    'PUT' => Http::timeout(30)
                        ->withToken($token)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->accept('application/json')
                        ->put($url, $body ?? []),
                    'DELETE' => Http::timeout(30)
                        ->withToken($token)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->accept('application/json')
                        ->delete($url),
                    default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
                };
            }

            if (! $response->successful()) {
                Log::error('SatuSehat API error', [
                    'method' => $method,
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('SatuSehat request exception', [
                'method' => $method,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function fhirUrl(string $path): string
    {
        $path = ltrim($path, '/');

        return $this->baseUrl . '/fhir-r4/v1/' . $path;
    }

    public function testWebhook(): array
    {
        if (! $this->webhookEnabled) {
            return [
                'ok' => false,
                'error' => 'Webhook belum diaktifkan. Aktifkan terlebih dahulu.',
            ];
        }

        if (empty($this->webhookUrl)) {
            return [
                'ok' => false,
                'error' => 'Webhook URL belum diisi.',
            ];
        }

        $token = $this->getToken();
        if (! $token) {
            return [
                'ok' => false,
                'error' => 'Gagal mendapatkan access token untuk mengirim webhook test.',
            ];
        }

        try {
            $response = Http::timeout(15)
                ->withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->accept('application/json')
                ->post($this->baseUrl . '/fhir-r4/v1/Subscription', [
                    'resourceType' => 'Subscription',
                    'status' => 'requested',
                    'reason' => 'Webhook configuration test from hospital system',
                    'criteria' => 'Patient?',
                    'channel' => [
                        'type' => 'rest-hook',
                        'endpoint' => $this->webhookUrl,
                        'payload' => 'application/fhir+json',
                    ],
                ]);

            if ($response->successful()) {
                $body = $response->json();

                return [
                    'ok' => true,
                    'message' => 'Webhook subscription berhasil dikirim ke Satu Sehat. Subscription ID: ' . ($body['id'] ?? 'N/A'),
                    'subscription_id' => $body['id'] ?? null,
                ];
            }

            return [
                'ok' => false,
                'error' => 'Gagal subscribe webhook: HTTP ' . $response->status() . ' — ' . ($response->body() ?? 'Unknown error'),
            ];
        } catch (\Throwable $e) {
            Log::error('SatuSehat webhook test failed: ' . $e->getMessage());

            return [
                'ok' => false,
                'error' => 'Gagal mengirim webhook subscription: ' . $e->getMessage(),
            ];
        }
    }

    public function getOrganizationId(): ?string
    {
        return $this->organizationId;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function getWebhookSecret(): ?string
    {
        return $this->webhookSecret;
    }

    public function isWebhookEnabled(): bool
    {
        return $this->webhookEnabled;
    }

    public function verifyWebhookSignature(string $payload, string $signatureHeader): bool
    {
        if (empty($this->webhookSecret)) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $this->webhookSecret);

        return hash_equals($expected, $signatureHeader);
    }
}

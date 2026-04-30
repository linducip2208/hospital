<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * License Client v3 — typed-key activation, server-signed payload, file lock.
 *
 * - .license.lock format: [16 nonce][16 tag][N ciphertext]
 *   ciphertext = AES-256-GCM(plaintext = json(signed_payload),
 *                            key      = HKDF-SHA256(APP_KEY . ":" . domain, "license-lock-v1"))
 * - Anti-tamper: payload signature verified against marketplace public RSA key.
 * - Heartbeat: cached 24h; grace 7d if marketplace unreachable.
 */
class LicenseClient
{
    private const HKDF_SALT     = 'license-lock-v1';
    private const HEARTBEAT_KEY = 'license:heartbeat:last';
    private const GRACE_KEY     = 'license:heartbeat:offline_since';

    /**
     * Check if the app is paired (has a valid .license.lock for this domain).
     */
    public function isPaired(string $domain): bool
    {
        return $this->readLock($domain) !== null;
    }

    /**
     * Verify the license for the given domain.
     * Returns decoded signed_payload data, or null if missing/tampered/invalid.
     * Performs heartbeat-on-stale (24h) with grace fallback (7d).
     */
    public function verify(string $domain): ?array
    {
        $payload = $this->readLock($domain);
        if (! $payload) {
            return null;
        }

        $data = $payload['data'] ?? null;
        if (! $data) {
            return null;
        }

        // Domain mismatch (file moved to a different host)
        if (($data['domain'] ?? null) !== strtolower($domain)) {
            return null;
        }

        // Expiry check
        if (! empty($data['expires_at']) && strtotime($data['expires_at']) < time()) {
            return null;
        }

        $this->maybeHeartbeat($data);

        return $data;
    }

    /**
     * Activate against marketplace.
     *
     * @return array{ok: bool, data: ?array, error: ?string}
     */
    public function activate(string $activationKey, string $domain): array
    {
        try {
            $resp = Http::timeout(config('license.http_timeout', 10))
                ->acceptJson()
                ->post($this->endpoint('/api/license/activate'), [
                    'activation_key' => $activationKey,
                    'domain'         => $domain,
                ]);
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Tidak bisa menghubungi server lisensi: ' . $e->getMessage()];
        }

        if ($resp->status() === 422 || $resp->status() === 403 || $resp->status() === 404) {
            return ['ok' => false, 'error' => $resp->json('error') ?? 'Aktivasi gagal.'];
        }

        if (! $resp->successful()) {
            return ['ok' => false, 'error' => 'Server error (HTTP ' . $resp->status() . ').'];
        }

        $body = $resp->json();
        if (! ($body['activated'] ?? false)) {
            return ['ok' => false, 'error' => $body['error'] ?? 'Aktivasi gagal.'];
        }

        $signed = $body['signed_payload'] ?? null;
        if (! $signed || ! $this->verifySignature($signed)) {
            return ['ok' => false, 'error' => 'Server response signature gagal divalidasi. Hubungi support.'];
        }

        // Encrypt and write to lock file
        $this->writeLock($signed, $domain);

        // Reset heartbeat cache so we don't immediately ping again
        Cache::put(self::HEARTBEAT_KEY, now()->timestamp, now()->addDays(7));
        Cache::forget(self::GRACE_KEY);

        return ['ok' => true, 'data' => $signed['data']];
    }

    /**
     * Delete the license lock file and clear heartbeat caches.
     */
    public function clearLock(): void
    {
        $path = config('license.lock_file');
        if (file_exists($path)) {
            @unlink($path);
        }
        Cache::forget(self::HEARTBEAT_KEY);
        Cache::forget(self::GRACE_KEY);
    }

    // ─────────────────────────────────────────────────────────────────
    // Heartbeat
    // ─────────────────────────────────────────────────────────────────

    private function maybeHeartbeat(array $data): void
    {
        $interval = config('license.heartbeat_interval', 86400);
        $last     = Cache::get(self::HEARTBEAT_KEY, 0);

        if ($last && (time() - $last) < $interval) {
            return;
        }

        try {
            $resp = Http::timeout(config('license.http_timeout', 10))
                ->acceptJson()
                ->post($this->endpoint('/api/license/heartbeat'), [
                    'installation_id' => $data['installation_id'],
                    'domain'          => $data['domain'],
                ]);
        } catch (\Throwable $e) {
            $this->markOffline();

            return;
        }

        $body = $resp->json();

        if (($body['ok'] ?? false) === true && ($body['status'] ?? '') === 'active') {
            Cache::put(self::HEARTBEAT_KEY, time(), now()->addDays(7));
            Cache::forget(self::GRACE_KEY);

            return;
        }

        // Server explicitly says revoked / expired / domain mismatch
        if (in_array($body['action'] ?? null, ['delete_license_file'], true)) {
            $this->clearLock();

            return;
        }

        // Otherwise treat as offline (5xx, non-decodable, etc.)
        $this->markOffline();
    }

    private function markOffline(): void
    {
        if (! Cache::has(self::GRACE_KEY)) {
            Cache::put(self::GRACE_KEY, time(), now()->addDays(30));
        }

        $offlineSince = Cache::get(self::GRACE_KEY, time());
        $grace        = config('license.heartbeat_grace', 604800); // 7 days

        if ((time() - $offlineSince) > $grace) {
            // Grace expired — block until marketplace reachable again
            $this->clearLock();
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // Crypto
    // ─────────────────────────────────────────────────────────────────

    /**
     * Read and decrypt the .license.lock file.
     * Returns the signed payload array, or null on any failure.
     */
    private function readLock(string $domain): ?array
    {
        $path = config('license.lock_file');
        if (! file_exists($path)) {
            return null;
        }

        $blob = file_get_contents($path);
        if ($blob === false || strlen($blob) < 32) {
            return null;
        }

        $nonce      = substr($blob, 0, 16);
        $tag        = substr($blob, 16, 16);
        $ciphertext = substr($blob, 32);

        $key   = $this->deriveKey($domain);
        $plain = openssl_decrypt(
            $ciphertext,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag,
        );

        if ($plain === false) {
            return null;
        }

        $payload = json_decode($plain, true);
        if (! is_array($payload)) {
            return null;
        }

        // Verify RSA signature
        if (! $this->verifySignature($payload)) {
            return null;
        }

        return $payload;
    }

    /**
     * Encrypt and write the signed payload to .license.lock.
     */
    private function writeLock(array $signedPayload, string $domain): void
    {
        $path = config('license.lock_file');
        $dir  = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $plain = json_encode($signedPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $key   = $this->deriveKey($domain);
        $nonce = random_bytes(16);

        $cipher = openssl_encrypt(
            $plain,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag,
            '',
            16
        );

        if ($cipher === false) {
            throw new \RuntimeException('Failed to encrypt license payload.');
        }

        file_put_contents($path, $nonce . $tag . $cipher, LOCK_EX);
        @chmod($path, 0600);
    }

    /**
     * Derive an AES-256 key from APP_KEY + domain using HKDF.
     */
    private function deriveKey(string $domain): string
    {
        $appKey = config('app.key');
        if (str_starts_with($appKey, 'base64:')) {
            $appKey = base64_decode(substr($appKey, 7));
        }

        $ikm = $appKey . ':' . strtolower($domain);

        return hash_hkdf('sha256', $ikm, 32, '', self::HKDF_SALT);
    }

    /**
     * Verify RSA signature of the signed payload using the marketplace public key.
     */
    private function verifySignature(array $payload): bool
    {
        $data      = $payload['data'] ?? null;
        $signature = $payload['signature'] ?? null;

        if (! $data || ! $signature) {
            return false;
        }

        $publicKeyPath = config('license.public_key_path');
        if (! file_exists($publicKeyPath)) {
            Log::error('marketplace.public.pem missing — cannot verify license signature.');

            return false;
        }

        $publicKey = openssl_pkey_get_public('file://' . $publicKeyPath);
        if ($publicKey === false) {
            return false;
        }

        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $sig  = base64_decode($signature, true);
        if ($sig === false) {
            return false;
        }

        $ok = openssl_verify($json, $sig, $publicKey, OPENSSL_ALGO_SHA256);

        return $ok === 1;
    }

    /**
     * Build a full endpoint URL from the configured server URL.
     */
    private function endpoint(string $path): string
    {
        return rtrim(config('license.server_url'), '/') . $path;
    }
}

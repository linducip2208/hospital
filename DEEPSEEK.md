# DEEPSEEK.md — Project Knowledge

## Hospital App License Pairing

This repository is a Laravel hospital application with a built-in license pairing flow.
The active runtime path uses `app/Services/LicenseClient.php` and `app/Http/Middleware/RequirePair.php`.

## Current License Flow

1. User opens the app on a fresh local/domain host.
2. `RequirePair` middleware checks `storage/app/.license.lock` using `LicenseClient::verify()`.
3. If the lock file is missing, invalid, expired, or domain-mismatched, the request is redirected to `/__pair`.
4. The pair wizard accepts an `activation_key` and calls `LicenseClient::activate()`.
5. Activation writes an encrypted lock file and returns the signed payload data.
6. Subsequent requests verify the RSA-signed payload and perform a heartbeat-on-stale check.
7. If heartbeat fails beyond the configured grace period, the lock is cleared and the app falls back to the pairing wizard.

## Key Files

- `config/license.php`
  - `server_url`
  - `public_key_path`
  - `lock_file`
  - heartbeat timing
  - `dev_bypass`

- `app/Services/LicenseClient.php`
  - activation endpoint: `POST /api/license/activate`
  - lock file format: AES-256-GCM encrypted JSON payload
  - RSA signature verification using `public_path('marketplace.public.pem')`
  - heartbeat interval + 7-day grace fallback

- `app/Http/Middleware/RequirePair.php`
  - blocks all web requests until pairing is valid
  - bypasses `/__pair`, `/up`, `_debugbar`, and localhost/.test hosts when `LICENSE_DEV_BYPASS=true`

- `routes/pair.php`
  - `GET /__pair`
  - `POST /__pair`
  - `GET /__pair/success`

- `app/Http/Controllers/PairController.php`
  - handles the activation wizard and success redirect

- `.license.lock`
  - stored at `storage/app/.license.lock`
  - ignored by git via `.gitignore`

## Local Development Setup

This app is configured for local MySQL development with Laragon:

- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=laravel`
- `DB_USERNAME=root`
- `DB_PASSWORD=`

The current local environment is using MySQL and migrations were successfully applied after creating the `laravel` database.

## Notes

- `app/Providers/LicenseServiceProvider.php`, `app/Services/LicenseChecker.php`, and `app/Http/Middleware/VerifyLicense.php` were legacy files and are no longer part of the active pairing flow.
- `.deepseek/logs/` is ignored by `.gitignore` and should not be committed.
- `storage/app/.license.lock` is also ignored and kept local only.

## Troubleshooting

- If `/__pair` still redirects unexpectedly, verify `APP_URL` and the host name match the paired domain.
- Ensure `marketplace.public.pem` exists and is readable by the application.
- For local dev, `LICENSE_DEV_BYPASS=true` allows localhost/test domains to bypass pairing.

## DeepSeek Memory Configuration

- User-level environment variable `NODE_OPTIONS=--max-old-space-size=8192` is set permanently via Windows `setx`.
- This means every DeepSeek session and any Node.js process automatically gets an 8 GB heap limit.
- To verify: `reg query "HKCU\Environment" /v NODE_OPTIONS`
- The script `deepseek-ram.cmd` also exists locally as a manual launcher.
- A helper script `setup-deepseek-memory.bat` can increase the Windows pagefile to 8 GB (requires admin).

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

- **RAM total: 16 GB** — `NODE_OPTIONS=--max-old-space-size=16384`
- User-level environment variable `NODE_OPTIONS=--max-old-space-size=16384` is set permanently via Windows `setx`.
- This means every DeepSeek session and any Node.js process automatically gets a **16 GB** heap limit.
- To verify: `reg query "HKCU\Environment" /v NODE_OPTIONS`
- The script `deepseek-ram.cmd` also exists locally as a manual launcher (sets 16 GB).
- A helper script `setup-deepseek-memory.bat` can increase the Windows pagefile to **16 GB** (requires admin).

## Application Structure

## Hospital App — Complete CRUD Structure

### Resources (Full CRUD)
- **Patients** — `PatientController`, `patients/*.blade.php`
- **Doctors** — `DoctorController`, `doctors/*.blade.php`
- **Treatments** — `TreatmentController`, `treatments/*.blade.php`
- **Appointments** — `AppointmentController`, `appointments/*.blade.php`
- **Medical Records** — `MedicalRecordController`, `medical-records/*.blade.php`
- **Payments** — `PaymentController`, `payments/*.blade.php`

### Admin Layout
- `resources/views/layouts/admin.blade.php` — Bootstrap 5 sidebar layout with nav links for all resources
- All resource views extend `layouts.admin`

### Model Field Mappings
- **MedicalRecord**: `action` ↔ `treatment_notes`, `medicine` ↔ `prescription`, `notes` ↔ `follow_up` (via Attribute accessors/mutators)
- **Payment**: `amount` ↔ `total` (via Attribute accessor/mutator), auto-generates `invoice_number` on create
- **Appointment**: `medicalRecord()` is `HasOne` (not HasMany), `start_time`/`end_time` cast as `datetime:H:i`

### Payment Status Values
`pending`, `completed`, `cancelled`, `refunded`

### Fixes Applied (this session)
- Created missing `layouts/admin.blade.php`
- Fixed `\` → `$` syntax in all broken blade views
- Added MedicalRecord field accessors for `action`/`medicine`/`notes`
- Added Payment `amount` accessor, auto-invoice, auto-patient_id
- Fixed Payment status enum via migration
- Added softDeletes to medical_records migration
- Updated Appointment casts (time columns) and relationship (HasOne)

## Database Schema & Fixes

## Database Rename Migration (2026_04_30_150000)

Applied the following changes to align database columns with application code:

### medical_records
- `treatment_notes` → `action`
- `prescription` → `medicine`
- `follow_up` → `notes`

### payments
- `total` → `amount`
- `payment_method` enum now supports: `cash, transfer, debit, credit, qris, card, insurance, other`
- `status` enum: `pending, completed, cancelled, refunded`

### New Indexes
- `medical_records.appointment_id` (idx_medical_records_appointment_id)
- `payments.patient_id` (idx_payments_patient_id)
- `payments.appointment_id` (idx_payments_appointment_id)

### Removed
- All Attribute accessors from MedicalRecord (`action`, `medicine`, `notes`) and Payment (`amount`) — no longer needed since DB columns now match field names

### Added
- User model: `hasOne(Patient)`, `hasOne(Doctor)` relations

### Controller Fixes
- Web PaymentController: payment_method validated against full enum list, uses sequential invoice number (INV/YYYY/MM/XXXXX), explicitly sets discount=0, tax=0
- API PaymentController: status enum fixed to `pending,completed,cancelled,refunded`, amount column name updated
- API MedicalRecordController: field names `action`/`medicine`/`notes` (consistent with web controller)

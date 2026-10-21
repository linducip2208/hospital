# SECURITY — hasil hardening audit 2026-10-21

## Yang diperbaiki (P0/P1)

1. **Pemalsuan status pembayaran** — `PaymentController@update` (web+API) tidak bisa lagi set `refunded/cancelled` langsung; pembayaran final tidak bisa diubah; hapus `completed/cancelled/refunded` dilarang (wajib refund resmi berjurnal). Web: butuh `billing.manage`; API: `billing.manage`, hapus admin/dev/finance.
2. **Refund resmi API** — `POST /api/v1/payments/{id}/refund` (permission `payments.refund`), validasi nominal ≤ sisa, jurnal pembalik idempoten.
3. **Bayar bill batal** — `BillingService::receivePayment` menolak bill `cancelled/refunded`.
4. **Jurnal pembayaran tunai** — store standalone (web+API) kini dalam transaksi + posting jurnal (best-effort bila COA belum ada, logged warning).
5. **Hapus via API dikunci** — generik default admin/developer; didedikasikan: patients/doctors/treatments → admin/dev; appointments → appointments.manage; medical-records → medical_records.sign; payments → admin/dev/finance.
6. **Modul sensitif API** — salaries/employees/journal/coa dibatasi per peran (`config/api_resources.php`).
7. **Pasien nonaktif** — login API + pemakaian token ditolak (403/401).
8. **Lisensi fail-closed** — default `dev_bypass=false`; bypass lokal eksplisit via `.env` (gitignored) + `APP_ENV=local`; `RequirePair` juga menjaga grup `api` (JSON 403); throttle pair GET; `TRUSTED_PROXIES` tidak lagi `*` di contoh.
9. **Token API** — hash SHA256, expiry 30 hari (maks 90), refresh rotasi, revoke staf+pasien, throttle auth 10/mnt.
10. **CORS** — `config/cors.php` khusus `api/*`, default tolak cross-origin browser.
11. **Scrub respons** — field `*password*/*token*/*secret*` dibuang di API generik; payload dokter publik disaring.
12. **Audit trail** — semua mutasi API generik + booking/ambulans publik dicatat (`via: api`).

## Model keamanan yang berlaku

- Web: `auth` + `permission:`/`role:` pada aksi sensitif; 97 controller CRUD mengandalkan auth (didokumentasikan, bukan diubah massal).
- API staf: Bearer token + `api.auth`; pasien: token + ownership milik-sendiri; publik: throttle + validasi + tanpa data internal.
- Login: throttle 5/mnt per email+IP, pesan generik, session regenerate, cookie Secure/HttpOnly/SameSite di production.

## Sisa risiko (lihat KNOWN-LIMITATIONS.md)

- RBAC web per-modul belum granular (auth-only di sebagian besar CRUD).
- 7 tabel klinis tanpa SoftDeletes (hapus permanen).
- Backup restore belum diuji end-to-end; integrasi live belum diuji.

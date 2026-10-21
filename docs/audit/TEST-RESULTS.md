# TEST-RESULTS (audit 2026-10-21, refresh kedua)

Perintah: `php artisan test` (SQLite :memory:, data sintetis, tanpa layanan eksternal).

## Ringkasan akhir

- **PASS: 175 · FAIL: 0 · BLOCKED: 0 · NOT RUN: 0** (583 asersi)
- Baseline awal: 161 → 165 (P0) → 170 (refresh) → 175 (hardening auth).

## Tes baru audit ini

| File | Isi | Hasil |
|---|---|---|
| `tests/Feature/FinancialIntegrityTest.php` | Tolak set-status refund langsung (web+API), tolak hapus completed, refund resmi + jurnal + validasi nominal, tolak bayar bill cancelled, cast config lisensi | 4/4 PASS |
| `tests/Feature/ClinicalWorkflowTest.php` | E2E encounter→charge→bill→jurnal→bayar parsial/lunas→refund+jurnal balik; idempotency key; diskon ≤ subtotal | 2/2 PASS |
| `tests/Feature/Http/ResourcePermissionTest.php` | Fail-open tanpa seed; staff baca-tolak-tulis obat; HR gaji; admin lolos; route eksplisit tak terpengaruh | 3/3 PASS |
| `tests/Feature/Api/*` (5 file) | Auth/expiry/refresh, CRUD generik 60 modul, scoping pasien, keamanan peran, API publik, smoke 60+ endpoint | semua PASS |
| `tests/Feature/AuthHardeningTest.php` | Lockout 10x/15 mnt + throttle login + pesan generik; throttle register per-IP; password min 8; revoke-all token; security headers API | 5/5 PASS |

## Tes regresi

Seluruh suite existing (auth, portal, blog, SEO, HTTP controllers, workflow, production hardening) tetap hijau sesudah perubahan pembayaran, lisensi, dan API.

## Static & build

- `composer validate`: VALID.
- Pint (Laravel preset): file yang disentuh audit dinormalisasi; suite tetap hijau sesudahnya.
- `npm run build` (vite 8.3.0): sukses, `public/build/manifest.json` + css/js terbit.

## Tidak dijalankan (NOT TESTED)

Integrasi live SatuSehat/BPJS/SMTP, restore backup end-to-end, load test — butuh kredensial/environment khusus (lihat BASELINE.md §6).

# TEST-RESULTS (audit 2026-10-21, refresh kedua)

Perintah: `php artisan test` (SQLite :memory:, data sintetis, tanpa layanan eksternal).

## Ringkasan akhir

- **PASS: 170 · FAIL: 0 · BLOCKED: 0 · NOT RUN: 0** (555 asersi, ~20–35 detik)
- Baseline awal: 161/161 → 165/165 (audit P0) → 170/170 (refresh: FinancialIntegrity 4 + ClinicalWorkflow 2 + ResourcePermission 3).

## Tes baru audit ini

| File | Isi | Hasil |
|---|---|---|
| `tests/Feature/FinancialIntegrityTest.php` | Tolak set-status refund langsung (web+API), tolak hapus completed, refund resmi + jurnal + validasi nominal, tolak bayar bill cancelled, cast config lisensi | 4/4 PASS |
| `tests/Feature/ClinicalWorkflowTest.php` | E2E encounter→charge→bill→jurnal→bayar parsial/lunas→refund+jurnal balik; idempotency key; diskon ≤ subtotal | 2/2 PASS |
| `tests/Feature/Http/ResourcePermissionTest.php` | Fail-open tanpa seed; staff baca-tolak-tulis obat; HR gaji; admin lolos; route eksplisit tak terpengaruh | 3/3 PASS |
| `tests/Feature/Api/*` (5 file, dari pekerjaan sebelumnya) | Auth/expiry/refresh, CRUD generik 60 modul, scoping pasien, keamanan peran, API publik, smoke 60+ endpoint | semua PASS |

## Tes regresi

Seluruh suite existing (auth, portal, blog, SEO, HTTP controllers, workflow, production hardening) tetap hijau sesudah perubahan pembayaran, lisensi, dan API.

## Static & build

- `composer validate`: VALID.
- Pint (Laravel preset): file yang disentuh audit dinormalisasi; suite tetap hijau sesudahnya.
- `npm run build` (vite 8.3.0): sukses, `public/build/manifest.json` + css/js terbit.

## Tidak dijalankan (NOT TESTED)

Integrasi live SatuSehat/BPJS/SMTP, restore backup end-to-end, load test — butuh kredensial/environment khusus (lihat BASELINE.md §6).

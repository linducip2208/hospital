# TEST-RESULTS (audit 2026-10-21)

Perintah: `php artisan test` (SQLite :memory:, data sintetis, tanpa layanan eksternal).

## Ringkasan akhir

- **PASS: 165 · FAIL: 0 · BLOCKED: 0 · NOT RUN: 0** (525 asersi, ~20–35 detik)
- Baseline awal: 161/161 → akhir 165/165 (tambahan: FinancialIntegrityTest 4 tes).

## Tes baru audit ini

| File | Isi | Hasil |
|---|---|---|
| `tests/Feature/FinancialIntegrityTest.php` | Tolak set-status refund langsung (web+API), tolak hapus completed, refund resmi + jurnal + validasi nominal, tolak bayar bill cancelled, cast config lisensi | 4/4 PASS |
| `tests/Feature/Api/*` (5 file, dari pekerjaan sebelumnya) | Auth/expiry/refresh, CRUD generik 60 modul, scoping pasien, keamanan peran, API publik, smoke 60+ endpoint | semua PASS |

## Tes regresi

Seluruh suite existing (auth, portal, blog, SEO, HTTP controllers, workflow, production hardening) tetap hijau sesudah perubahan pembayaran, lisensi, dan API.

## Static & build

- `composer validate`: VALID.
- Pint (Laravel preset): file yang disentuh audit dinormalisasi; suite tetap hijau sesudahnya.
- `npm run build` (vite 8.3.0): sukses, `public/build/manifest.json` + css/js terbit.

## Tidak dijalankan (NOT TESTED)

Integrasi live SatuSehat/BPJS/SMTP, restore backup end-to-end, load test — butuh kredensial/environment khusus (lihat BASELINE.md §6).

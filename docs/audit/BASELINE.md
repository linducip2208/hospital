# BASELINE — Audit SIMRS Hospital

Tanggal: 2026-10-21 · Cabang: `main` · Commit awal audit: `01b15dc`
Lingkungan audit: Windows, PHP 8.3.30, Laravel Framework 13.31.0, MySQL 8.4.9 (lokal), PHPUnit 12.5.12, SQLite :memory: (testing).

## 1. Status Git & file

- `git status`: bersih (tidak ada perubahan lokal tak-commit saat mulai).
- Branch aktif: `main`, sinkron dengan `origin/main` (sesudah push terakhir).
- `.env` lokal: `APP_ENV=local`, `APP_DEBUG=true`, DB `hospital@localhost`. Kredensial produksi TIDAK disentuh.

## 2. Dependensi & versi

- `composer.json`: `php ^8.3`, `laravel/framework ^13.0`, `barryvdh/laravel-dompdf ^3.1`, `laravel/tinker ^3.0`. Dev: faker, pail, pao, pint, mockery, collision, phpunit 12.
- `composer validate`: VALID.
- Frontend: Bootstrap 5.3.3 + Bootstrap Icons via CDN, Vite (`vite.config.js`), font Bunny. Tidak ada framework SPA.

## 3. Struktur & skala (hasil inventarisasi)

| Area | Jumlah | Keterangan |
|---|---|---|
| Controller (`app/Http/Controllers`) | 101 file | Web ±83, `Api/*` 11, `Portal/*` 5, `Admin/*` 2, `Auth/*` 2 |
| Model (`app/Models`) | 93 file | 58 SoftDeletes, 35 tanpa SoftDeletes |
| Migration | 87 file → 105 tabel | Termasuk `api_tokens`, `patient_api_tokens` (baru) |
| Route web (mentah) | 197 decl | 24 publik + 173 auth (termasuk portal 12, pair 3) |
| Endpoint HTTP aktual (estimasi ekspansi resource) | ±450 web + ±57 API | Lihat `docs/audit/FEATURE-MATRIX.md` |
| Permission (seeder) | 25 | 8 role non-admin; admin/developer bypass via kode |
| Endpoint API sensitif tanpa auth | 7 | webhook + 4 auth + 6 publik terkurasi (throttle) |

## 4. Hasil baseline test (SEBELUM perbaikan audit)

```
php artisan test → 161 passed, 508 assertions, 0 gagal (35 detik)
```
Baseline hijau — menjadi acuan regresi untuk seluruh perubahan audit.

## 5. Temuan awal prioritas (ringkasan; detail di FINAL-AUDIT.md)

P0 — diperbaiki dalam audit ini:
1. Web `PaymentController@edit/update/destroy` tanpa permission + bisa set `refunded/cancelled` langsung (pemalsuan status, tanpa jurnal).
2. API `PaymentController@update` pola sama; belum ada endpoint refund resmi.
3. `BillingService::receivePayment` menerima bill `cancelled/refunded`.
4. Pembayaran standalone (tanpa bill) tidak dijurnal → laporan under-report.
5. `config/license.php` default `dev_bypass=true`; API tidak dicek lisensi sama sekali (jalur bypass penuh).
6. `TRUSTED_PROXIES=*` di contoh production.
7. Migrasi lama drop kolom `payments.status` (potensi data loss nilai `paid/partial`).
8. Generic API: TypeError mapping parameter; patient guard 500; throttle merusak smoke test.

P1 — ditangani/didokumentasikan:
- 97 controller web hanya dijaga `auth` (tanpa policy per-aksi); didokumentasikan sebagai KNOWN-LIMITATIONS dengan mitigasi bertahap.
- 7 model klinis/operasional tanpa SoftDeletes (IcuMonitoring, MedicationAdministration, NurseAssignment, Partograph, Queue, ShiftHandover, VitalSignsRecord).

## 6. Batasan environment audit

- Integrasi live SatuSehat/BPJS, gateway pembayaran sungguhan, dan SMTP tidak diuji (tanpa kredensial) → status NOT TESTED, hanya verifikasi kode + mock.
- Restore backup tidak dijalankan (butuh environment terisolasi) → NOT TESTED, prosedur diverifikasi dari kode.
- Load test tidak dijalankan → NOT TESTED.

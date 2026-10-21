# DEPLOYMENT (audit) — langkah aman + rollback

Rujukan utama tetap `PRODUCTION-DEPLOY.md`, `DEPLOYMENT.md`, `deploy.sh`. Tambahan dari audit ini:

## Update rutin

```bash
cd /var/www/hospital
php artisan down --secret=bypass
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force   # wajib: patient_api_tokens + expires_at + normalisasi status
php artisan optimize:clear
php artisan config:cache route:cache view:cache
php artisan up
```

## Pemeriksaan pasca-deploy

```bash
php artisan production:check --strict   # termasuk deteksi LICENSE_DEV_BYPASS
php artisan satusehat:diagnose          # tanpa kredensial = tunjukkan KOSONG, bukan error
php artisan test --filter=Api          # opsional di staging
```

## Rollback

- Kode: `git reset --hard <commit-sebelumnya>` + `config/route/view:cache` ulang.
- Database: `php artisan migrate:rollback --step=2` (hanya 2 migrasi audit; token pasien hilang — user login ulang).
- Lisensi: jangan hapus `.license.lock`; bila `APP_ENV` salah `local` di produksi + `LICENSE_DEV_BYPASS=true`, pairing terbuka — pastikan `.env` produksi: `APP_ENV=production`, `LICENSE_DEV_BYPASS=false`, `APP_DEBUG=false`, `TRUSTED_PROXIES` spesifik.
- Darurat: `php artisan down` + restore backup terakhir (`db:backup` harian, retensi 30 hari).

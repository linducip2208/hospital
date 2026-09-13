# Deployment

1. Siapkan PHP 8.3+, MySQL 8+, Nginx, Supervisor, Node.js, dan TLS.
2. Jalankan `composer install --no-dev --optimize-autoloader`, siapkan `.env` dengan APP_KEY, APP_URL, database, cache/session/queue, mail, dan credential integrasi.
3. Backup database lalu `php artisan migrate --force`; build asset dengan `npm ci && npm run build`.
4. Jalankan `php artisan production:check --strict`, `php artisan optimize`, queue worker via Supervisor, dan scheduler via `php artisan schedule:work`.
5. Pastikan `storage`/`bootstrap/cache` writable dan public document root adalah `/public`.
6. Monitor failed jobs, backup, disk, error rate, health readiness (`/health/ready`), dan status sync BPJS/SatuSehat.

Jangan memakai credential demo pada produksi; ubah password semua akun seeder dan set `LICENSE_DEV_BYPASS=false`.

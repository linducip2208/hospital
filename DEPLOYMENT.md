# Production Deployment

Panduan utama ada di [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md). Ringkasnya:

1. Siapkan PHP 8.3+, MySQL 8+, Nginx, Node.js, Supervisor, TLS, dan user database least-privilege.
2. Salin `.env.example` menjadi `.env`, isi `APP_KEY`, `APP_URL`, database, queue, mail, dan secret integrasi secara aman.
3. Jalankan `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`, lalu `npm ci && npm run build`.
4. Set `APP_ENV=production`, `APP_DEBUG=false`, `LICENSE_DEV_BYPASS=false`, ubah seluruh password demo, dan pastikan `storage` serta `bootstrap/cache` writable.
5. Jalankan `deploy/supervisor.conf` untuk queue worker dan `php artisan schedule:work` (atau scheduler OS setiap menit).
6. Jalankan `php artisan production:check --strict`, lalu verifikasi backup restore, failed jobs, audit trail, RBAC, HTTPS, patient portal ownership, dan UAT BPJS/SatuSehat sebelum go-live.

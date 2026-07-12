# SIMRS Hospital — Production Package

## Isi Paket

| File | Deskripsi |
|------|-----------|
| `hospital-db-dump.sql` | Full database export (schema + data) — ~12 MB |
| `deploy.sh` | Auto-deploy script untuk Ubuntu 22.04/24.04 |
| `*` (semua source code) | Laravel 13 project files (tanpa vendor, node_modules, .git) |

## Cara Deploy ke VPS

### Opsi 1: Auto Deploy (rekomendasi)

```bash
# Upload semua file ke server
scp -r ./* root@SERVER-IP:/root/hospital-deploy/

# SSH ke server lalu jalankan
ssh root@SERVER-IP
cd /root/hospital-deploy
chmod +x deploy.sh
sudo bash deploy.sh --domain rumahsakit-anda.id --db-pass PasswordKuatMin16
```

### Opsi 2: Manual Deploy

```bash
# 1. Copy project
cp -r . /var/www/hospital
cd /var/www/hospital

# 2. Setup .env (isi konfigurasi production)
cp .env.production.example .env
nano .env

# 3. Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 4. Import database
mysql -u root -p hospital_prod < hospital-db-dump.sql

# 5. Laravel setup
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Permissions
chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache
```

## Requirements Server

- **OS:** Ubuntu 22.04 / 24.04 (64-bit)
- **PHP:** 8.3+ (dengan extension: mysql, mbstring, xml, bcmath, gd, zip, intl, curl)
- **MySQL:** 8.0+
- **Web Server:** Nginx (recommended) atau Apache
- **Node.js:** 20.x LTS (untuk build frontend)
- **Disk:** Minimal 2 GB free
- **RAM:** Minimal 2 GB

## Default Admin Login

Setelah import SQL dump, login dengan:

| Role | Email | Password |
|------|-------|----------|
| Developer | developer@hospital.test | password |
| Admin | admin@hospital.test | password |
| Dokter | doctor1@hospital.test | password |
| Perawat | nurse1@hospital.test | password |
| Bidan | bidan1@hospital.test | password |
| Farmasi | pharmacy1@hospital.test | password |
| Lab | lab1@hospital.test | password |
| Radiologi | radiology1@hospital.test | password |
| Keuangan | finance1@hospital.test | password |
| HR | hr1@hospital.test | password |
| IGD | emergency1@hospital.test | password |

> **GANTI SEMUA PASSWORD setelah deploy pertama kali!**

## Post-Deploy Checklist

- [ ] Domain HTTPS aktif & SSL valid
- [ ] Buka `/__pair` — pair license dari `whitelabel.co.id`
- [ ] Login dengan semua role, pastikan bisa akses
- [ ] Cek menu cetak (medical certificates, informed consents, dll.)
- [ ] Cek PDF ter-render dengan rapi
- [ ] Cek dashboard statistik muncul
- [ ] Ganti password semua user
- [ ] Setup backup cron (ada di deploy.sh)

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Error 500 | `php artisan optimize:clear` lalu cek `storage/logs/laravel.log` |
| Asset 404 | `npm run build`, cek `public/build/` |
| Logo tidak muncul | `php artisan storage:link` |
| Nginx 502 | Cek PHP-FPM: `systemctl status php8.3-fpm` |
| DB connection error | Cek `.env` DB_HOST/DB_PORT/DB_USERNAME/DB_PASSWORD |
| License blocked | Pastikan `LICENSE_DEV_BYPASS=false`, re-pair via `/__pair` |

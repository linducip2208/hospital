# Panduan Deploy ke Production

Server target: VPS Linux (Ubuntu 22.04 / Debian 12) dengan PHP 8.3, MySQL 8, Nginx/Apache.

## 1. Persiapan Server

### Install Stack
```bash
sudo apt update
sudo apt install -y nginx mysql-server php8.3 php8.3-fpm php8.3-mysql \
    php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-gd php8.3-zip \
    php8.3-intl php8.3-curl php8.3-redis git unzip composer

# Node.js LTS (untuk build asset)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo bash -
sudo apt install -y nodejs
```

### Buat Database
```sql
mysql -u root -p
CREATE DATABASE hospital_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'hospital_user'@'localhost' IDENTIFIED BY 'PasswordKuatMin16Karakter!';
GRANT ALL PRIVILEGES ON hospital_prod.* TO 'hospital_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 2. Clone & Setup Project

```bash
cd /var/www
sudo git clone <repo-url> hospital
cd hospital
sudo chown -R www-data:www-data .

# Setup .env
sudo cp .env.production.example .env
sudo nano .env  # isi sesuai production: APP_KEY, DB password, MAIL, dll

# Install dependency
composer install --no-dev --optimize-autoloader
npm ci
npm run build         # ← build minified asset ke public/build/

# Setup Laravel
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force        # jalankan UserSeeder + PageContentSeeder + MassiveDataSeeder

# Atau jalankan terpisah kalau cuma butuh sebagian:
# php artisan db:seed --class=UserSeeder --force
# php artisan db:seed --class=PageContentSeeder --force

# Optimize cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Permission
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## 3. Konfigurasi Nginx

`/etc/nginx/sites-available/hospital`:
```nginx
server {
    listen 80;
    server_name domain-anda.id www.domain-anda.id;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name domain-anda.id www.domain-anda.id;
    root /var/www/hospital/public;

    ssl_certificate     /etc/letsencrypt/live/domain-anda.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/domain-anda.id/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php;
    charset utf-8;

    # Service Worker — JANGAN cache file SW
    location /sw.js {
        add_header Cache-Control "no-cache, no-store, must-revalidate";
        try_files $uri =404;
    }

    # Static asset — cache 1 tahun
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|webp)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
    client_max_body_size 10M;
}
```

```bash
sudo ln -s /etc/nginx/sites-available/hospital /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 4. SSL Certificate (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d domain-anda.id -d www.domain-anda.id
sudo systemctl enable certbot.timer  # auto-renew
```

## 5. Pairing License v3

Setelah pertama deploy, akses domain → akan redirect ke `/__pair`. Masukkan activation key dari marketplace `whitelabel.co.id`.

## 6. Production Check List

- [ ] `.env` punya `APP_ENV=production`
- [ ] `.env` punya `APP_DEBUG=false`
- [ ] `.env` punya `LICENSE_DEV_BYPASS=false`
- [ ] `APP_URL` pakai `https://`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] DB user **bukan root**
- [ ] `php artisan config:cache` sudah jalan
- [ ] `npm run build` sudah jalan (asset di `public/build/`)
- [ ] SSL certificate aktif (cek `https://domain-anda.id`)
- [ ] Permission `storage/` dan `bootstrap/cache/` writable oleh www-data
- [ ] Backup database aktif (lihat #7 di bawah)
- [ ] `php artisan storage:link` sudah jalan (untuk gambar CMS upload)

## 7. Backup Otomatis (Cron)

`crontab -e` (sebagai user www-data atau root):
```cron
# Backup DB setiap hari jam 2 pagi
0 2 * * * mysqldump -u hospital_user -pPASSWORD hospital_prod | gzip > /backup/db-$(date +\%Y\%m\%d).sql.gz

# Hapus backup > 30 hari
0 3 * * * find /backup -name "db-*.sql.gz" -mtime +30 -delete

# Laravel scheduler (kalau pakai)
* * * * * cd /var/www/hospital && php artisan schedule:run >> /dev/null 2>&1
```

## 8. Update Berkala (Manual Deploy)

```bash
cd /var/www/hospital
sudo -u www-data php artisan down --secret=bypass
sudo -u www-data git pull origin main
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data npm ci && sudo -u www-data npm run build
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan config:cache route:cache view:cache
sudo systemctl reload php8.3-fpm
sudo -u www-data php artisan up
```

## 9. Troubleshooting

| Issue | Solusi |
|---|---|
| Error 500 setelah deploy | `php artisan optimize:clear` lalu cek `storage/logs/laravel.log` |
| Asset 404 | `npm run build` lalu cek `public/build/` ada |
| Logo tidak tampil | `php artisan storage:link` |
| CMS edit tidak menyimpan | Cek FK `page_contents` table — jangan delete record yang ada |
| License blocked | Cek `LICENSE_DEV_BYPASS=false`, lalu re-pair lewat `/__pair` |
| Slow first load | Pastikan `php artisan config:cache` & `route:cache` jalan |
| Service Worker not registered | Cek HTTPS aktif (SW butuh secure context, kecuali localhost) |

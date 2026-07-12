# 🏥 SIMRS Hospital — Production Database Deployment Guide

Panduan lengkap deploy database ke server produksi (DB sudah ada tapi kosong, format MySQL/MariaDB).

---

## 📦 File yang Disediakan

Semua file ada di folder `database/production/`:

| File | Ukuran | Fungsi |
|---|---|---|
| **`production-install.sql`** | 120 KB | ⭐ **Pakai ini** — bundle schema + master data (77 tabel + 71 record essential) |
| `01-schema.sql` | ~100 KB | Schema saja (CREATE TABLE) — opsional kalau butuh terpisah |
| `02-master-data.sql` | ~20 KB | Master data saja (INSERT) — opsional kalau schema sudah ada |

---

## 🚀 Cara Deploy — 3 Pilihan

### Pilihan A: Via phpMyAdmin (paling gampang, untuk shared hosting)

1. Login ke **cPanel** atau **phpMyAdmin** server.
2. Pilih database kosong yang sudah disiapkan (mis. `simrs_prod`).
3. Klik tab **"Import"**.
4. **Choose File** → pilih `database/production/production-install.sql`.
5. Pastikan **Format = SQL**, **Character set = utf8mb4**.
6. Klik **"Go"** / **"Import"**.
7. Tunggu sampai muncul pesan "Import has been successfully finished".

### Pilihan B: Via MySQL CLI (untuk VPS/dedicated server)

```bash
# Upload file production-install.sql ke server (via SCP/SFTP/Git)
scp database/production/production-install.sql user@your-server:/tmp/

# SSH ke server
ssh user@your-server

# Import ke database
mysql -u USERNAME -p NAMA_DATABASE < /tmp/production-install.sql

# Verifikasi
mysql -u USERNAME -p NAMA_DATABASE -e "SHOW TABLES;" | wc -l
# Output expected: 78 (77 tabel + 1 header)
```

### Pilihan C: Via Laravel Artisan (kalau punya akses SSH + PHP CLI)

```bash
# 1. Clone/upload kode aplikasi ke server
git clone https://github.com/your-repo/hospital.git /var/www/hospital
cd /var/www/hospital

# 2. Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 3. Copy .env, set DB credentials di .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
cp .env.example .env
nano .env  # edit DB config + APP_URL + APP_KEY

# 4. Generate APP_KEY (kalau belum)
php artisan key:generate

# 5. Run migration & seed master essential
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder --force

# 6. Cache config & route untuk performa
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

---

## 🔐 Setelah Import — WAJIB Lakukan Ini

### 1. Login & Ganti Password Default

Buka aplikasi di browser, login dengan:
- **Email:** `admin@hospital.test`
- **Password:** `password`

**Segera lakukan:**
- Buka menu **SDM & Master Data → Pengguna Sistem**
- Edit user `admin` → ganti email ke email asli pemilik RS, **ganti password**
- Edit user `director` dan `it.support` juga
- Atau hapus akun demo, buat akun baru dari nol

### 2. Update File `.env` di Server

Pastikan minimal field berikut sudah benar di production `.env`:

```ini
APP_NAME="Nama Rumah Sakit Anda"
APP_ENV=production
APP_DEBUG=false                # ⚠️ WAJIB false di production
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=db_user
DB_PASSWORD=db_password_kuat

# Mail (untuk reset password, notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com       # atau provider email lain
MAIL_PORT=587
MAIL_USERNAME=noreply@domain-anda.com
MAIL_PASSWORD=app_password
MAIL_FROM_ADDRESS=noreply@domain-anda.com
MAIL_FROM_NAME="${APP_NAME}"

# Session & Cache (production)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 3. Konfigurasi Integrasi (opsional)

Login sebagai admin → menu **Sistem & Integrasi**:
- **Branding** — Upload logo, set warna, nama RS
- **BPJS** — Isi BPJS API credentials (kalau pakai)
- **Satu Sehat** — Isi SATUSEHAT API (kalau pakai)
- **CMS Tampilan** — Edit landing page content

---

## 📊 Isi Master Data Essential (sudah di-seed)

Setelah import, database akan punya data dasar berikut (siap pakai):

| Tabel | Jumlah | Isi |
|---|---|---|
| `users` | 3 | admin, director, it.support |
| `departments` | 8 | Administrasi, Medis, Keperawatan, Farmasi, Keuangan, SDM, IT, Manajemen |
| `polyclinics` | 12 | Umum, Gigi, Anak, Jantung, Bedah, Obgyn, Mata, THT, Kulit, Saraf, Jiwa, Fisio |
| `rooms` | 7 | Sample 1 kamar per tipe: VIP, Kelas 1-3, ICU, NICU, OK |
| `treatments` | 8 | Konsultasi, EKG, Imunisasi, Suntik Vitamin, Cabut/Tambal Gigi, dll. |
| `drugs` | 8 | Paracetamol, Amoxicillin, Ibuprofen, dll. (obat dasar) |
| `chart_of_accounts` | 25 | COA struktur PSAK (Aset, Kewajiban, Ekuitas, Pendapatan, Beban) |
| **Total** | **71 record** | + 77 tabel kosong siap diisi |

---

## ✅ Verifikasi Berhasil

Setelah import & deploy, test:

```bash
# 1. Cek jumlah tabel
mysql -u USER -p DB_NAME -e "SHOW TABLES;" | wc -l
# Expected: 78 (77 tabel + header)

# 2. Cek admin user ada
mysql -u USER -p DB_NAME -e "SELECT email, role FROM users;"
# Expected: 3 row (admin/director/it.support)

# 3. Cek FK constraint aktif
mysql -u USER -p DB_NAME -e "
SELECT COUNT(*) AS total_fk
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME IS NOT NULL;
"
# Expected: 121

# 4. Test login via web
# Buka https://domain-anda.com/login
# Email: admin@hospital.test, Password: password
# Harus berhasil masuk ke dashboard
```

---

## 🔄 Backup Strategy (setelah produksi jalan)

### Backup harian otomatis (cronjob)

```bash
# Tambah di crontab (crontab -e)
# Backup setiap hari jam 02:00 pagi
0 2 * * * mysqldump -u USER -pPASSWORD DB_NAME | gzip > /backup/simrs_$(date +\%Y\%m\%d).sql.gz

# Hapus backup > 30 hari
0 3 * * * find /backup -name "simrs_*.sql.gz" -mtime +30 -delete
```

### Backup manual sebelum update

```bash
# Sebelum update aplikasi / run migration
mysqldump -u USER -pPASSWORD DB_NAME > /tmp/backup_pre_update_$(date +%Y%m%d_%H%M).sql

# Kalau ada masalah, restore:
mysql -u USER -pPASSWORD DB_NAME < /tmp/backup_pre_update_TIMESTAMP.sql
```

---

## 🆘 Troubleshooting

### Error "Foreign key constraint fails"
**Penyebab:** Urutan INSERT salah atau ada data referensi yang belum ada.
**Solusi:** Pastikan `SET FOREIGN_KEY_CHECKS=0;` ada di awal file (sudah ada di `production-install.sql`).

### Error "Specified key was too long; max key length is 767 bytes"
**Penyebab:** MySQL versi lama (< 5.7.7) tidak support utf8mb4 dengan VARCHAR(255).
**Solusi:**
```sql
SET GLOBAL innodb_file_format = Barracuda;
SET GLOBAL innodb_file_per_table = ON;
SET GLOBAL innodb_large_prefix = ON;
```
Atau upgrade ke MySQL 5.7.7+ / MariaDB 10.2+.

### Error "Table already exists"
**Penyebab:** Database tidak kosong, sudah ada tabel.
**Solusi:** Drop & re-create database, atau hapus tabel manual dulu.

### Login tidak bisa setelah import
**Penyebab:** Cache aplikasi.
**Solusi:**
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

---

## 📞 Support

Kalau ada error spesifik saat deploy, kirimkan:
1. Output error lengkap
2. Versi MySQL/MariaDB (`mysql --version`)
3. Versi PHP (`php --version`)
4. Apakah pakai shared hosting / VPS / cloud

---

## 📁 Struktur File

```
hospital/
├── database/
│   ├── migrations/          ← 62 file migration (kalau pakai cara C)
│   ├── seeders/
│   │   └── ProductionSeeder.php  ← seeder master essential
│   └── production/
│       ├── 01-schema.sql               ← schema saja (1795 baris)
│       ├── 02-master-data.sql          ← master data saja (170 INSERT)
│       └── production-install.sql      ← ⭐ BUNDLE (pakai ini)
└── PRODUCTION-DEPLOY-DB.md  ← file ini
```

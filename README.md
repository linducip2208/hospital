# 🏥 Sistem Informasi Rumah Sakit (SIRS) — Hospital ERP

Aplikasi **ERP Rumah Sakit** lengkap berbasis Laravel 13 — mencakup manajemen pasien, dokter, 12 poli, farmasi, laboratorium, radiologi, rawat inap, IGD, ruang bersalin, bank darah, operasi, ambulans, keperawatan, kebidanan, HR, penggajian, akuntansi, aset, logistik, dan banyak lagi. **White-label ready** — semua branding bisa diubah dari admin panel.

---

## 🚀 Quick Start

```bash
git clone <repo-url> hospital
cd hospital
cp .env.example .env
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Buka `http://localhost:8000` — landing page siap. Login ke `/login`.

## Production ERP Upgrade

Core workflow sekarang memakai `Encounter` sebagai clinical hub: appointment/antrian, rekam medis, diagnosis ICD-10, clinical order, lab/radiologi, resep, FEFO dispensing, charge/bill/invoice, payment allocation, refund, journal, admission, bed movement, discharge, audit trail, RBAC, dan queue SatuSehat dapat ditelusuri dalam satu perjalanan pasien.

Dokumentasi operasional:

- [Arsitektur](docs/ARCHITECTURE.md) · [Patient Journey](docs/PATIENT-JOURNEY.md)
- [Billing](docs/BILLING-FLOW.md) · [Farmasi](docs/PHARMACY-FLOW.md) · [Rawat Inap](docs/INPATIENT-FLOW.md)
- [BPJS](docs/BPJS-INTEGRATION.md) · [SatuSehat](docs/SATUSEHAT-INTEGRATION.md) · [Permissions](docs/PERMISSIONS.md)
- [API v1](docs/API.md) · [Security](docs/SECURITY.md) · [Deployment](docs/DEPLOYMENT.md)
- [Full upgrade report](docs/UPGRADE-REPORT.md)

### Verifikasi instalasi dan release

```bash
composer validate --no-check-publish
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan test
npm ci && npm run build
```

Untuk API eksternal gunakan bearer token dari `POST /api/auth/tokens`; jangan memakai session browser untuk integrasi pihak ketiga. Jalankan queue worker dan scheduler sesuai [DEPLOYMENT.md](docs/DEPLOYMENT.md). Submit `/sitemap.xml` ke Google Search Console setelah `APP_URL` produksi diisi.

BPJS dan SatuSehat tidak dianggap live hanya karena konfigurasi tersimpan: tanpa credential dan UAT fasilitas kesehatan, UI tetap menampilkan mode simulation/offline.

---

## 🔑 Default Login

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@hospital.test | password |
| Dokter | dr.andi@hospital.test | password |
| Staff | staff@hospital.test | password |
| Perawat | perawat.wati@hospital.test | password |
| Bidan | bidan.sari@hospital.test | password |
| Apoteker | apt.dian@hospital.test | password |
| Kasir | kasir.budi@hospital.test | password |
| Lab Teknisi | lab.eko@hospital.test | password |
| IT Support | it.support@hospital.test | password |
| Finance | finance@hospital.test | password |
| HR Manager | hr@hospital.test | password |
| Direktur | director@hospital.test | password |

---

## 📊 Statistik Sistem

| Metric | Value |
|--------|-------|
| **Tabel Database** | 90+ |
| **Route** | 601 |
| **Kontroller** | 90+ |
| **Model** | 70+ |
| **View** | 340+ |
| **Migration** | 90+ |
| **Seeder** | 16+ |
| **Role User** | 12 |

---

## 🏗️ Arsitektur Database (Relational)

```
users ──┬── patients ──┬── appointments ──┬── medical_records
        │              │                  ├── payments
        │              │                  └── treatments
        │              ├── lab_tests (FK patient + doctor)
        │              ├── radiologies (FK patient + doctor)
        │              ├── maternities (FK patient + doctor)
        │              ├── emergencies (FK patient + doctor)
        │              ├── surgeries (FK patient + doctor)
        │              ├── referrals (FK patient + doctor + 2 polyclinics)
        │              ├── nurse_assignments (FK patient + user)
        │              ├── blood_donations (FK patient nullable)
        │              ├── vital_signs_records (FK patient + nurse)
        │              ├── medication_administrations (FK patient + nurse + drug)
        │              ├── nursing_cares (FK patient + nurse)
        │              ├── anc_records (FK patient + midwife)
        │              ├── postnatal_records (FK patient + midwife + maternity)
        │              ├── baby_immunizations (FK patient + maternity)
        │              └── partographs (FK maternity)
        │
        ├── doctors ────┬── appointments
        │               ├── medical_records
        │               ├── doctor_polyclinic (pivot)
        │               ├── lab_tests, radiologies
        │               ├── maternities, surgeries
        │               ├── emergencies, referrals, queues
        │               └── polyclinics (many-to-many)
        │
        ├── employees ─── salaries
        ├── staff_schedules
        ├── attendances
        ├── leaves
        ├── nurse_assignments
        └── departments ─── users + assets

polyclinics ──┬── doctor_polyclinic (pivot)
              ├── queues (FK patient + doctor)
              └── referrals (from/to)

ambulances ── ambulance_calls
purchase_orders ── purchase_order_items
journal_entries ── journal_entry_lines (FK chart_of_accounts)
chart_of_accounts ── self-referencing parent/children
```

**Total: 56 Foreign Key relationships** — semua tabel terhubung secara relasional.

---

## 📋 Daftar Modul Lengkap

### 📊 Dashboard
- 6 stat cards real-time
- Appointment & pembayaran terbaru
- Dark mode toggle
- AI insight panel

### 👤 Master Data
| Modul | Route | Fitur |
|-------|-------|-------|
| Pasien | `/patients` | NIK, BPJS, golongan darah, alergi, riwayat medis, kontak darurat |
| Dokter | `/doctors` | Spesialisasi, STR, biaya konsultasi, status (aktif/cuti) |
| Pengguna | `/users` | 12 role, username, password, assign ke departemen |
| Departemen | `/departments` | 8 departemen (Administrasi, Medis, Keperawatan, Farmasi, Keuangan, SDM, IT, Manajemen) |
| Karyawan | `/employees` | NIP, jabatan, gaji pokok, bank, BPJS TK, NPWP, status |
| Jadwal Staff | `/staff-schedules` | Shift pagi/siang/malam per user per tanggal |

### 🏥 Poli & Klinik (12 Poli)
| Kode | Nama Poli | Lantai |
|------|-----------|--------|
| UMUM | Poli Umum | Lt 1 |
| GIGI | Poli Gigi | Lt 1 |
| JANTUNG | Poli Jantung | Lt 2 |
| SARAF | Poli Saraf | Lt 2 |
| MATA | Poli Mata | Lt 2 |
| THT | Poli THT | Lt 2 |
| KULIT | Poli Kulit & Kelamin | Lt 3 |
| ANAK | Poli Anak | Lt 3 |
| OBGYN | Poli Kebidanan | Lt 3 |
| BEDAH | Poli Bedah | Lt 4 |
| ORTHO | Poli Orthopedi | Lt 4 |
| PDLM | Poli Penyakit Dalam | Lt 4 |

| Sub-Modul | Route | Fitur |
|-----------|-------|-------|
| Antrian | `/queues` | Nomor otomatis `KODE-001`, status waiting→called→completed, panggil & selesai |
| Appointment | `/appointments` | Jadwal pasien-dokter, workflow scheduled→confirmed→in_progress→completed |
| Treatment | `/treatments` | Katalog tindakan + harga + durasi + persyaratan |
| Rujukan | `/referrals` | Rujuk antar poli, pending→approved→rejected→completed |

### ⚕️ Pelayanan Medis
| Modul | Route | Fitur |
|-------|-------|-------|
| Rekam Medis | `/medical-records` | Diagnosis, tindakan, obat, vital signs (JSON), hasil lab |
| Operasi / OT | `/surgeries` | Jadwal operasi, tipe, anestesi, OT room, pre-op→post-op |
| IGD | `/emergencies` | Triase merah/kuning/hijau/hitam, arrival mode, status 24J |
| Ruang Bersalin | `/maternities` | Normal/Caesar/Vacuum, data bayi lengkap, admitted→discharged |

### 🩺 Keperawatan (Nursing ERP)
| Modul | Route | Fitur |
|-------|-------|-------|
| Penugasan | `/nurse-assignments` | Tugas perawat/bidan ke pasien, shift pagi/siang/malam |
| Tanda Vital | `/vital-signs` | TD, HR, RR, SpO2, suhu — monitoring real-time |
| Pemberian Obat | `/medication-administrations` | Obat + dosis + rute (oral/IV/IM/SC) + jam pemberian |
| Asuhan Keperawatan | `/nursing-cares` | SOAP: subjektif, objektif, diagnosis, rencana, tindakan, evaluasi |
| Serah Terima Shift | `/shift-handovers` | Handover pagi→siang→malam, ringkasan pasien, catatan penting |

### 👶 Kebidanan (Midwife ERP)
| Modul | Route | Fitur |
|-------|-------|-------|
| Pemeriksaan Hamil | `/anc-records` | ANC 1-10+, usia gestasi, TFU, DJJ, Hb, protein urine, TT, Fe, skor risiko |
| Partograf | `/partographs` | Pembukaan, penurunan kepala, kontraksi, amniotic fluid, oksitosin |
| Perawatan Nifas | `/postnatal-records` | TFU, lochia, luka perineum, ASI, kontrasepsi |
| Imunisasi Bayi | `/baby-immunizations` | Vaksin, jadwal, dosis, status scheduled→given→missed |

### 🔬 Penunjang Medis
| Modul | Route | Fitur |
|-------|-------|-------|
| Laboratorium | `/lab-tests` | Hematologi, Kimia Darah, Urinalisis, Mikrobiologi, Serologi |
| Radiologi | `/radiologies` | Thorax, CT Scan, MRI, USG, Mamografi |
| Bank Darah | `/blood-donations` | Golongan darah A/B/AB/O ±, stok ml, expiry 42 hari |
| Farmasi | `/drugs` | 20 obat, Tablet/Sirup/Injeksi, stok, harga |
| Rawat Inap | `/rooms` | VIP s/d Kelas 3, 15 kamar, fasilitas, status |

### 🚑 Ambulans
| Modul | Route | Fitur |
|-------|-------|-------|
| Armada | `/ambulances` | 5 unit, supir, status tersedia/bertugas/maintenance |
| Panggilan Darurat | `/ambulance-calls` | Dispatch, alamat jemput, status pending→completed |

### 💰 Keuangan & Akuntansi
| Modul | Route | Fitur |
|-------|-------|-------|
| Pembayaran | `/payments` | Multi metode (cash/transfer/debit/credit/QRIS/insurance), invoice `INV/YYYY/MM/XXXXX` |
| Laporan | `/reports` | Revenue per bulan, appointment by status, top treatments |
| Chart of Accounts | `/chart-of-accounts` | 20 akun (aset, liabilitas, ekuitas, pendapatan, beban) |
| Jurnal Umum | `/journal-entries` | Double-entry, debit/kredit, posting, nomor otomatis |

### 👔 HR & Penggajian
| Modul | Route | Fitur |
|-------|-------|-------|
| Absensi | `/attendances` | Check-in/out harian, status present/late/absent/sick/leave |
| Penggajian | `/salaries` | Gaji pokok + lembur + bonus - potongan, approve & pay |
| Cuti & Izin | `/leaves` | Annual/sick/maternity/paternity, approve/reject workflow |

### 📦 Logistik
| Modul | Route | Fitur |
|-------|-------|-------|
| Aset & Inventaris | `/assets` | 10 aset (medis, IT, kendaraan, furniture), kondisi, status |
| Purchase Order | `/purchase-orders` | PO number otomatis, supplier, items, status draft→received |

### ⚙️ Sistem
| Modul | Route | Fitur |
|-------|-------|-------|
| Pengaturan | `/settings` | Umum, Satu Sehat API, BPJS API |
| **Branding** ✨ | `/settings/branding` | **White-label**: logo, nama app, hero title, footer — semua editable |
| CMS Tampilan | `/cms` | Edit konten landing page (hero, features, CTA, about) |
| Tutorial | `/tutorial` | 22 section panduan lengkap |

---

## 🔌 Integrasi API

### Satu Sehat (Kemenkes)
- Endpoint: `Admin → Pengaturan → Satu Sehat`
- Konfigurasi: Base URL, Client ID, Client Secret, Organization ID

### BPJS Kesehatan
- Endpoint: `Admin → Pengaturan → BPJS`
- Konfigurasi: Base URL, Consumer ID, Consumer Secret, User Key
- Data pasien: nomor BPJS, status verifikasi NIK

### REST API
- Prefix: `/api/v1/`
- Modul: patients, doctors, treatments, appointments, medical-records, payments
- Format: JSON, pagination support
- Calendar endpoint: `/api/v1/appointments/calendar`

---

## 🎨 White-label & Branding

Semua branding bisa diubah dari **Admin → Pengaturan → Branding** tanpa sentuh code:

| Setting | Fungsi |
|---------|--------|
| App Name | Nama di sidebar, landing page, title browser |
| Logo URL | Ganti logo sidebar |
| Favicon URL | Ganti ikon tab browser |
| Footer Text | Copyright footer |
| Hero Title | Headline landing page |
| Hero Subtitle | Sub-headline landing page |

---

## 🎨 UI/UX Features

- **Dark Mode** — toggle dengan localStorage persistence
- **Animasi** — page fade-in, pulse badges (IGD 24J), hover cards, shimmer loading
- **Sidebar** — gradient dark dengan glow indicator, 14 collapsible accordion groups
- **Popup Pembelian** — full-screen overlay, WhatsApp CTA, muncul 1x per session
- **Mobile Responsive** — sidebar overlay di mobile
- **Breadcrumb** auto-generated
- **Notification Bell** dengan pulse dot animation

---

## 📖 Dokumentasi Publik

Akses dokumentasi lengkap tanpa login: **`/docs`**

16 section: Pendahuluan, Memulai, Master Data, Poli & Klinik, Appointment, Rekam Medis, IGD & Bersalin, Keperawatan, Kebidanan, Penunjang Medis, Keuangan, HR & Payroll, Akuntansi, Logistik, Pengaturan, FAQ.

---

## 🛡️ Security

- **RBAC Middleware** — `CheckRole` (12 role-based) dan `CheckPoliAccess` (poli-based)
- **License System** — pairing wizard via `whitelabel.co.id`, RSA-signed lock file, AES-256-GCM encryption
- **CSRF Protection** — semua form
- **Password Hashing** — bcrypt 12 rounds
- **Soft Deletes** — semua modul utama
- **Dev Bypass** — `LICENSE_DEV_BYPASS=true` untuk development localhost

---

## 📦 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13.x (PHP 8.3+) |
| Database | MySQL 8.4 (52 tabel, 56 FK) |
| Frontend | Bootstrap 5.3 + Bootstrap Icons |
| Font | Plus Jakarta Sans (Google Fonts) |
| Build | Vite 8 |
| Session | Database driver |
| Queue | Database driver |
| Cache | Database driver |

---

## 🔧 Development

```bash
# Fresh install dengan seed
php artisan migrate:fresh --seed

# Clear cache
php artisan optimize:clear

# Run dev server
php artisan serve

# List semua route
php artisan route:list

# Cek semua FK relationships
mysql -uroot laravel -e "SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='laravel' AND REFERENCED_TABLE_NAME IS NOT NULL ORDER BY TABLE_NAME"
```

---

## 📁 Struktur Proyek

```
hospital/
├── app/
│   ├── Http/Controllers/    # 40+ controllers (Web + API)
│   ├── Http/Middleware/     # RequirePair, CheckRole, CheckPoliAccess
│   ├── Models/              # 35+ models
│   └── Services/            # LicenseClient
├── database/
│   ├── migrations/          # 55 migrations
│   ├── factories/            # 7 factories
│   └── seeders/             # 14 seeders
├── resources/views/
│   ├── layouts/             # admin.blade.php (premium theme)
│   ├── patients/            # CRUD views
│   ├── doctors/             # CRUD views
│   ├── ...                  # 30+ view directories
│   ├── docs.blade.php       # Dokumentasi publik
│   └── welcome.blade.php    # Landing page (CMS-powered)
├── routes/
│   ├── web.php              # 339 routes
│   ├── api.php              # REST API v1
│   └── pair.php             # License wizard
└── storage/app/             # License lock file
```

---

## 📱 Contact

Untuk source code lengkap + lisensi + support:
- **WhatsApp: 0812-9605-2010**
- Gratis konsultasi & bantuan instalasi
- White-label — bisa rebranding sesuka hati

---

© 2026 Sistem Informasi Rumah Sakit. Built with Laravel. All rights reserved.

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumentasi Penggunaan - {{ config('app.name') }}</title>
    <meta name="description" content="Panduan lengkap penggunaan {{ config('app.name') }} — Sistem Informasi Manajemen Rumah Sakit.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --accent: #0891b2;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            color: var(--gray-800);
            background: var(--gray-50);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            scroll-behavior: smooth;
        }

        /* ── Nav ── */
        .docs-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--gray-100);
        }
        .docs-nav-inner {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .docs-nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--gray-900);
        }
        .docs-nav-brand .icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1rem; font-weight: 700;
        }
        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.5rem 1.25rem;
            border-radius: 9px;
            font-size: 0.85rem; font-weight: 600;
            text-decoration: none;
            color: var(--gray-600);
            background: var(--gray-100);
            transition: all 0.2s;
        }
        .btn-back:hover { background: var(--gray-200); color: var(--gray-800); }

        /* ── Hero ── */
        .docs-hero {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 4rem 1.5rem 3.5rem;
            text-align: center;
        }
        .docs-hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
        }
        .docs-hero p {
            font-size: 1.05rem;
            opacity: 0.85;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ── Layout ── */
        .docs-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 2.5rem;
            align-items: start;
        }

        /* ── Sidebar ── */
        .docs-sidebar {
            position: sticky;
            top: 80px;
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-100);
            padding: 1.25rem;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }
        .docs-sidebar h5 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-400);
            margin-bottom: 0.75rem;
            font-weight: 700;
        }
        .docs-sidebar a {
            display: block;
            padding: 0.45rem 0.75rem;
            border-radius: 7px;
            font-size: 0.85rem;
            color: var(--gray-600);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.15s;
        }
        .docs-sidebar a:hover { background: var(--gray-50); color: var(--primary); }
        .docs-sidebar a.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }

        /* ── Content ── */
        .docs-content h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }
        .docs-content h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-top: 2rem;
            margin-bottom: 0.75rem;
        }
        .docs-content p {
            color: var(--gray-600);
            margin-bottom: 1rem;
        }
        .docs-content ul, .docs-content ol {
            margin-bottom: 1rem;
            padding-left: 1.25rem;
            color: var(--gray-600);
        }
        .docs-content li { margin-bottom: 0.35rem; }
        .docs-section {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-100);
            padding: 2rem;
            margin-bottom: 1.5rem;
            scroll-margin-top: 80px;
        }
        .docs-section::before {
            content: '';
            display: block;
            height: 4px;
            margin: -2rem -2rem 1.5rem -2rem;
            border-radius: 12px 12px 0 0;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        .docs-badge {
            display: inline-block;
            padding: 0.2rem 0.7rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            background: var(--primary-light);
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        .demo-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 1rem 0 1.25rem;
            font-size: 0.88rem;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            overflow: hidden;
        }
        .demo-table thead th {
            background: var(--gray-50);
            text-align: left;
            padding: 0.7rem 0.9rem;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--gray-500);
            font-weight: 700;
            border-bottom: 1px solid var(--gray-200);
        }
        .demo-table tbody td {
            padding: 0.65rem 0.9rem;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-700);
        }
        .demo-table tbody tr:last-child td { border-bottom: none; }
        .demo-table tbody tr:hover { background: var(--gray-50); }
        .demo-table td code,
        .demo-table th code {
            background: var(--gray-100);
            padding: 0.15rem 0.45rem;
            border-radius: 5px;
            font-size: 0.8rem;
            color: var(--primary-dark);
        }
        .demo-table .role-badge {
            display: inline-block;
            padding: 0.15rem 0.55rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            background: var(--primary-light);
            color: var(--primary);
        }
        .demo-table .role-badge.admin { background: #fef3c7; color: #92400e; }
        .demo-table .role-badge.developer { background: #ede9fe; color: #6d28d9; }
        .demo-table .role-badge.doctor { background: #dcfce7; color: #166534; }
        .demo-table .role-badge.director { background: #fce7f3; color: #9d174d; }
        .demo-note {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 0.85rem 1rem;
            border-radius: 6px;
            font-size: 0.88rem;
            color: #78350f;
            margin: 1rem 0;
        }
        .demo-note strong { color: #92400e; }
        .login-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 0.5rem;
            padding: 0.55rem 1.1rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.15s;
        }
        .login-cta:hover { background: var(--primary-dark); color: white; }
        .docs-shot-row { display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(260px, .85fr); gap: 2rem; align-items: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-100); }
        .docs-shot-row.reverse .docs-shot-frame { order: 2; }
        .docs-shot-frame { overflow: hidden; border: 1px solid var(--gray-200); border-radius: 12px; background: #f8fafc; box-shadow: 0 14px 34px rgba(15,23,42,.10); }
        .docs-shot-chrome { display: flex; align-items: center; gap: 6px; padding: 9px 12px; border-bottom: 1px solid var(--gray-200); background: #fff; }
        .docs-shot-chrome span { width: 8px; height: 8px; border-radius: 50%; }
        .docs-shot-chrome span:nth-child(1) { background: #fb7185; } .docs-shot-chrome span:nth-child(2) { background: #fbbf24; } .docs-shot-chrome span:nth-child(3) { background: #34d399; }
        .docs-shot-url { flex: 1; overflow: hidden; margin-left: 8px; padding: 4px 9px; border: 1px solid var(--gray-200); border-radius: 5px; color: var(--gray-500); font: 600 .67rem/1 ui-monospace, SFMono-Regular, Consolas, monospace; white-space: nowrap; text-overflow: ellipsis; }
        .docs-shot-frame img { display: block; width: 100%; height: auto; aspect-ratio: 16 / 10; object-fit: cover; object-position: top left; }
        .docs-shot-copy h3 { margin-top: 0; color: var(--gray-900); } .docs-shot-copy ul { margin-bottom: 0; }
        @media (max-width: 900px) { .docs-shot-row { grid-template-columns: 1fr; } .docs-shot-row.reverse .docs-shot-frame { order: 0; } }
        @media (max-width: 640px) {
            .demo-table-wrap { overflow-x: auto; }
            .demo-table { min-width: 560px; }
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .docs-container { grid-template-columns: 1fr; }
            .docs-sidebar {
                display: none;
            }
            .docs-hero h1 { font-size: 1.75rem; }
        }
    </style>
</head>
<body>

<nav class="docs-nav">
    <div class="docs-nav-inner">
        <a href="/" class="docs-nav-brand">
            <span class="icon">+</span>
            {{ config('app.name', 'SIRS') }}
        </a>
        <a href="/" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Aplikasi
        </a>
    </div>
</nav>

<section class="docs-hero">
    <h1>Panduan Lengkap Penggunaan Sistem</h1>
    <p>Pelajari seluruh fitur dan cara menggunakan {{ config('app.name', 'SIRS') }} dari dasar hingga mahir.</p>
</section>

<div class="docs-container">
    <aside class="docs-sidebar">
        <h5>Daftar Isi</h5>
        <a href="#pendahuluan" class="active">1. Pendahuluan</a>
        <a href="#akun-demo">2. Akun Demo & Login</a>
        <a href="#memulai">3. Memulai</a>
        <a href="#master-data">4. Master Data</a>
        <a href="#poli-klinik">5. Poli & Klinik</a>
        <a href="#appointment">6. Appointment</a>
        <a href="#rekam-medis">7. Rekam Medis</a>
        <a href="#igd-bersalin">8. IGD & Bersalin</a>
        <a href="#keperawatan">9. Keperawatan</a>
        <a href="#kebidanan">10. Kebidanan</a>
        <a href="#penunjang">11. Penunjang Medis</a>
        <a href="#keuangan">12. Keuangan</a>
        <a href="#hr-payroll">13. HR & Payroll</a>
        <a href="#akuntansi">14. Akuntansi</a>
        <a href="#logistik">15. Logistik</a>
        <a href="#pengaturan">16. Pengaturan</a>
        <a href="#screenshot-fitur">17. Screenshot Fitur</a>
        <a href="#faq">18. FAQ</a>
    </aside>

    <main class="docs-content">

        {{-- 1. Pendahuluan --}}
        <section id="pendahuluan" class="docs-section">
            <span class="docs-badge">Bab 1</span>
            <h2>Pendahuluan</h2>
            <p><strong>{{ config('app.name', 'SIRS') }}</strong> adalah Sistem Informasi Manajemen Rumah Sakit (SIMRS) berbasis web yang dirancang untuk mengelola seluruh operasional rumah sakit dalam satu platform terintegrasi.</p>
            <h3>Fitur Utama</h3>
            <ul>
                <li><strong>Manajemen Pasien</strong> — Data pasien lengkap, riwayat alergi, kontak darurat, dan status aktif.</li>
                <li><strong>Manajemen Dokter</strong> — Kelola spesialisasi, STR, biaya konsultasi, dan status dokter.</li>
                <li><strong>Appointment & Antrian</strong> — Scheduling janji temu, sistem antrian poli, status real-time.</li>
                <li><strong>Rekam Medis Digital</strong> — Diagnosis, tindakan medis, resep obat, vital signs tercatat aman.</li>
                <li><strong>Farmasi & Inventaris Obat</strong> — Kelola stok obat, kategori, harga satuan, dan tracking.</li>
                <li><strong>Rawat Inap</strong> — Manajemen kamar, fasilitas, tarif, dan status ketersediaan.</li>
                <li><strong>IGD & Bersalin</strong> — Triase IGD, pencatatan persalinan, rujukan internal.</li>
                <li><strong>Laboratorium & Radiologi</strong> — Pencatatan hasil lab, radiologi, dan bank darah.</li>
                <li><strong>Keuangan & Pembayaran</strong> — Multi metode pembayaran, invoice otomatis, laporan keuangan.</li>
                <li><strong>HR & Payroll</strong> — Data karyawan, absensi, penggajian, cuti, departemen.</li>
                <li><strong>Akuntansi</strong> — Chart of accounts, jurnal umum, buku besar.</li>
                <li><strong>Logistik</strong> — Manajemen aset dan purchase order.</li>
                <li><strong>Integrasi</strong> — Satu Sehat (SATUSEHAT), BPJS Kesehatan, CMS landing page.</li>
            </ul>
        </section>

        {{-- 2. Akun Demo & Login --}}
        <section id="akun-demo" class="docs-section">
            <span class="docs-badge">Bab 2</span>
            <h2>Akun Demo & Login</h2>
            <p>Sistem ini sudah dilengkapi <strong>14 akun demo</strong> dengan role berbeda yang dapat digunakan untuk eksplorasi semua fitur. Semua akun demo menggunakan password yang sama: <code>password</code></p>

            <a href="{{ route('login') }}" class="login-cta">
                <i class="bi bi-box-arrow-in-right"></i> Buka Halaman Login
            </a>

            <div class="demo-note">
                <strong>Catatan keamanan:</strong> Akun demo di bawah ini <em>hanya untuk testing/demonstrasi</em>. Segera ganti password atau hapus akun demo sebelum sistem digunakan di production.
            </div>

            <h3>Daftar Akun Demo</h3>
            <div class="demo-table-wrap">
                <table class="demo-table">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Email Login</th>
                            <th>Username</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="role-badge developer">Developer (CMS)</span></td>
                            <td><code>developer@hospital.test</code></td>
                            <td><code>developer</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge admin">Admin Rumah Sakit</span></td>
                            <td><code>admin@hospital.test</code></td>
                            <td><code>admin</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge director">Direktur</span></td>
                            <td><code>director@hospital.test</code></td>
                            <td><code>director</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge doctor">Dokter</span></td>
                            <td><code>dr.andi@hospital.test</code></td>
                            <td><code>dr.andi</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">Perawat</span></td>
                            <td><code>nurse.rina@hospital.test</code></td>
                            <td><code>nurse.rina</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">Perawat</span></td>
                            <td><code>perawat.wati@hospital.test</code></td>
                            <td><code>perawat.wati</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">Bidan</span></td>
                            <td><code>bidan.sari@hospital.test</code></td>
                            <td><code>bidan.sari</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">Apoteker</span></td>
                            <td><code>apt.dian@hospital.test</code></td>
                            <td><code>apt.dian</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">Lab Teknisi</span></td>
                            <td><code>lab.eko@hospital.test</code></td>
                            <td><code>lab.eko</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">Kasir</span></td>
                            <td><code>kasir.budi@hospital.test</code></td>
                            <td><code>kasir.budi</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">Staff Administrasi</span></td>
                            <td><code>staff@hospital.test</code></td>
                            <td><code>staff</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">Finance</span></td>
                            <td><code>finance@hospital.test</code></td>
                            <td><code>finance.staff</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">HR Manager</span></td>
                            <td><code>hr@hospital.test</code></td>
                            <td><code>hr.manager</code></td>
                            <td><code>password</code></td>
                        </tr>
                        <tr>
                            <td><span class="role-badge">IT Support</span></td>
                            <td><code>it.support@hospital.test</code></td>
                            <td><code>it.support</code></td>
                            <td><code>password</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3>Cara Login</h3>
            <ol>
                <li>Buka halaman <code>/login</code> atau klik tombol <strong>Buka Halaman Login</strong> di atas.</li>
                <li>Masukkan <strong>email</strong> dan <strong>password</strong> sesuai role yang ingin dicoba.</li>
                <li>Setelah login berhasil, sistem akan otomatis redirect ke dashboard.</li>
                <li>Setiap role memiliki menu & hak akses berbeda — silakan eksplorasi.</li>
            </ol>

            <h3>Membuat Akun Baru</h3>
            <p>Untuk membuat akun pengguna baru: login sebagai <strong>Admin</strong> → buka menu <strong>Master Data → Pengguna</strong> → klik tombol <strong>Tambah Pengguna</strong>. Isi data lengkap, pilih role, lalu simpan. Pengguna baru langsung dapat login dengan kredensial yang dibuat.</p>

            <h3>Reset Password</h3>
            <p>Saat ini reset password dilakukan oleh admin melalui menu <strong>Master Data → Pengguna → Edit</strong>. Fitur self-service reset password (via email) akan tersedia di rilis berikutnya.</p>
        </section>

        {{-- 3. Memulai --}}
        <section id="memulai" class="docs-section">
            <span class="docs-badge">Bab 3</span>
            <h2>Memulai</h2>
            <h3>Login ke Sistem</h3>
            <p>Akses halaman login melalui <code>/login</code>. Masukkan email dan password yang telah didaftarkan. Jika belum memiliki akun, gunakan menu <strong>Daftar</strong> untuk membuat akun baru.</p>
            <h3>Dashboard</h3>
            <p>Setelah login, Anda akan diarahkan ke dashboard yang menampilkan ringkasan informasi penting:</p>
            <ul>
                <li>Total pasien, dokter, dan appointment hari ini</li>
                <li>Grafik appointment dan pendapatan</li>
                <li>Status poli dan antrian terkini</li>
                <li>Quick actions untuk menu yang sering digunakan</li>
            </ul>
            <h3>Navigasi Sidebar</h3>
            <p>Sidebar kiri adalah navigasi utama yang terbagi dalam grup menu:</p>
            <ul>
                <li><strong>Dashboard</strong> — Kembali ke halaman utama</li>
                <li><strong>Master Data</strong> — Data dasar: pasien, dokter, user, departemen</li>
                <li><strong>Operasional</strong> — Poliklinik, appointment, antrian, rekam medis, laboratorium</li>
                <li><strong>Farmasi</strong> — Manajemen obat dan stok</li>
                <li><strong>Rawat Inap</strong> — Kamar, ambulan, rujukan</li>
                <li><strong>Keuangan</strong> — Pembayaran, invoice, laporan</li>
                <li><strong>HR</strong> — Karyawan, absensi, gaji, cuti</li>
                <li><strong>Logistik</strong> — Aset, purchase order</li>
                <li><strong>Sistem</strong> — Pengaturan, CMS, tutorial</li>
            </ul>
        </section>

        {{-- 4. Master Data --}}
        <section id="master-data" class="docs-section">
            <span class="docs-badge">Bab 4</span>
            <h2>Master Data</h2>
            <p>Master data adalah data dasar yang menjadi fondasi seluruh operasional sistem.</p>
            <h3>Pasien</h3>
            <ul>
                <li>Menu: <strong>Master Data → Pasien</strong></li>
                <li>Tambah pasien baru dengan NIK, nama lengkap, tanggal lahir, jenis kelamin, alamat, nomor telepon, alergi, riwayat penyakit, dan kontak darurat.</li>
                <li>Gunakan fitur <strong>search</strong> untuk mencari pasien berdasarkan nama atau NIK.</li>
                <li>Status pasien: Aktif / Nonaktif.</li>
            </ul>
            <h3>Dokter</h3>
            <ul>
                <li>Menu: <strong>Master Data → Dokter</strong></li>
                <li>Tambahkan dokter dengan nama, spesialisasi, STR, biaya konsultasi, nomor telepon, dan email.</li>
                <li>Status dokter: Available / Busy / Off.</li>
            </ul>
            <h3>Pengguna (User)</h3>
            <ul>
                <li>Menu: <strong>Master Data → Pengguna</strong></li>
                <li>Kelola akun pengguna dengan 7 role: admin, dokter, staff, nurse, pharmacist, cashier, lab technician.</li>
                <li>Setiap role memiliki hak akses yang berbeda sesuai kebutuhan.</li>
            </ul>
            <h3>Departemen</h3>
            <ul>
                <li>Menu: <strong>HR → Departemen</strong></li>
                <li>Buat departemen seperti Poli Umum, Farmasi, Keuangan, HRD, dll.</li>
                <li>Digunakan untuk mengelompokkan karyawan.</li>
            </ul>
            <h3>Karyawan</h3>
            <ul>
                <li>Menu: <strong>HR → Karyawan</strong></li>
                <li>Catat data karyawan lengkap: NIP, nama, jabatan, departemen, tanggal bergabung, gaji pokok, dan status.</li>
            </ul>
            <h3>Treatment (Tindakan Medis)</h3>
            <ul>
                <li>Menu: <strong>Master Data → Treatment</strong></li>
                <li>Daftar tindakan medis dengan nama, harga, durasi (menit), kategori, dan deskripsi.</li>
            </ul>
        </section>

        {{-- 5. Poli & Klinik --}}
        <section id="poli-klinik" class="docs-section">
            <span class="docs-badge">Bab 5</span>
            <h2>Poli & Klinik</h2>
            <h3>Membuat Poli</h3>
            <ul>
                <li>Menu: <strong>Operasional → Poliklinik</strong></li>
                <li>Tambahkan poli baru: nama (contoh: Poli Umum, Poli Gigi), kode, deskripsi, dan status (Buka/Tutup).</li>
                <li>Setiap poli bisa memiliki jam operasional dan kapasitas antrian.</li>
            </ul>
            <h3>Mengatur Antrian</h3>
            <ul>
                <li>Menu: <strong>Operasional → Antrian</strong></li>
                <li>Pasien yang mendaftar akan mendapat nomor antrian otomatis per poli.</li>
                <li>Status antrian: Waiting → Called → In Progress → Completed.</li>
                <li>Gunakan tombol <strong>Panggil</strong> untuk memanggil pasien dan <strong>Selesai</strong> untuk menandai selesai.</li>
            </ul>
            <h3>Rujukan</h3>
            <ul>
                <li>Menu: <strong>Operasional → Rujukan</strong></li>
                <li>Buat rujukan dari Poli A ke Poli B dengan alasan dan diagnosis.</li>
                <li>Status: Pending → Approved → Rejected → Completed.</li>
            </ul>
        </section>

        {{-- 6. Appointment --}}
        <section id="appointment" class="docs-section">
            <span class="docs-badge">Bab 6</span>
            <h2>Appointment (Janji Temu)</h2>
            <ul>
                <li>Menu: <strong>Operasional → Appointment</strong></li>
                <li>Buat janji temu untuk pasien dengan memilih dokter, poli, tanggal, dan jam.</li>
                <li>Status appointment: Scheduled → Confirmed → In Progress → Completed → Cancelled.</li>
                <li>Gunakan tombol status untuk mengubah status appointment sesuai alur.</li>
                <li>Filter berdasarkan tanggal, status, dokter, atau poli.</li>
            </ul>
            <h3>Alur Status</h3>
            <ol>
                <li><strong>Scheduled</strong> — Janji temu baru dibuat</li>
                <li><strong>Confirmed</strong> — Pasien konfirmasi kehadiran</li>
                <li><strong>In Progress</strong> — Pasien sedang diperiksa</li>
                <li><strong>Completed</strong> — Pemeriksaan selesai</li>
                <li><strong>Cancelled</strong> — Dibatalkan</li>
            </ol>
        </section>

        {{-- 7. Rekam Medis --}}
        <section id="rekam-medis" class="docs-section">
            <span class="docs-badge">Bab 7</span>
            <h2>Rekam Medis</h2>
            <ul>
                <li>Menu: <strong>Operasional → Rekam Medis</strong></li>
                <li>Catat diagnosis (primer dan sekunder), tindakan medis yang dilakukan, dan resep obat.</li>
                <li>Setiap rekam medis terhubung ke pasien, dokter, appointment, dan poli.</li>
                <li>Tambahkan catatan khusus dan rekomendasi lanjutan.</li>
            </ul>
            <h3>Komponen Rekam Medis</h3>
            <ul>
                <li><strong>Diagnosis</strong> — Kode ICD (opsional) dan deskripsi diagnosis</li>
                <li><strong>Tindakan</strong> — Pilih dari katalog treatment, sesuaikan jumlah dan harga</li>
                <li><strong>Obat / Resep</strong> — Pilih obat dari inventaris farmasi, tentukan dosis dan aturan pakai</li>
                <li><strong>Vital Signs</strong> — TD, nadi, suhu, RR, SpO2 (opsional)</li>
            </ul>
        </section>

        {{-- 8. IGD & Bersalin --}}
        <section id="igd-bersalin" class="docs-section">
            <span class="docs-badge">Bab 8</span>
            <h2>IGD & Bersalin</h2>
            <h3>IGD (Instalasi Gawat Darurat)</h3>
            <ul>
                <li>Menu: <strong>Operasional → IGD</strong></li>
                <li>Sistem triase IGD dengan tingkat kegawatan: Hijau (ringan) → Kuning (sedang) → Merah (darurat).</li>
                <li>Catat keluhan utama, tindakan darurat, dan disposisi (rawat inap / pulang / rujuk).</li>
            </ul>
            <h3>Ruang Bersalin</h3>
            <ul>
                <li>Menu: <strong>Operasional → Ruang Bersalin</strong></li>
                <li>Pencatatan persalinan lengkap: data ibu, bayi, jenis persalinan (normal/C-section), komplikasi, dan kondisi bayi.</li>
                <li>Catat berat dan panjang bayi, APGAR score, dan jenis kelamin.</li>
            </ul>
        </section>

        {{-- 9. Keperawatan --}}
        <section id="keperawatan" class="docs-section">
            <span class="docs-badge">Bab 9</span>
            <h2>Keperawatan</h2>
            <h3>Tanda Vital</h3>
            <ul>
                <li>Menu: <strong>Keperawatan → Tanda Vital</strong></li>
                <li>Catat tanda vital pasien: tekanan darah (sistolik/diastolik), nadi, suhu tubuh, respiratory rate, SpO2.</li>
                <li>Riwayat vital signs tersimpan per pasien untuk monitoring berkala.</li>
            </ul>
            <h3>Pemberian Obat</h3>
            <ul>
                <li>Menu: <strong>Keperawatan → Pemberian Obat</strong></li>
                <li>Catat pemberian obat kepada pasien sesuai resep dokter.</li>
                <li>Tracking: obat, dosis, rute, waktu pemberian, dan perawat yang memberikan.</li>
            </ul>
            <h3>Asuhan Keperawatan</h3>
            <ul>
                <li>Menu: <strong>Keperawatan → Asuhan Keperawatan</strong></li>
                <li>Buat rencana asuhan keperawatan: assessment, diagnosis keperawatan, intervensi, implementasi, evaluasi.</li>
            </ul>
            <h3>Shift Handover</h3>
            <ul>
                <li>Menu: <strong>Keperawatan → Serah Terima Shift</strong></li>
                <li>Catat serah terima antar shift perawat: kondisi pasien, obat yang sudah/telah diberikan, catatan penting.</li>
            </ul>
        </section>

        {{-- 10. Kebidanan --}}
        <section id="kebidanan" class="docs-section">
            <span class="docs-badge">Bab 10</span>
            <h2>Kebidanan</h2>
            <h3>ANC (Antenatal Care)</h3>
            <ul>
                <li>Menu: <strong>Kebidanan → Pemeriksaan ANC</strong></li>
                <li>Catat pemeriksaan kehamilan: usia kehamilan, berat badan, tekanan darah, tinggi fundus, denyut jantung janin, dan imunisasi TT.</li>
                <li>Tracking kunjungan ANC per trimester.</li>
            </ul>
            <h3>Partograf</h3>
            <ul>
                <li>Menu: <strong>Kebidanan → Partograf</strong></li>
                <li>Pencatatan kemajuan persalinan menggunakan partograf WHO: pembukaan serviks, penurunan kepala, kontraksi, dan kondisi ibu/janin.</li>
            </ul>
            <h3>Nifas (Postnatal)</h3>
            <ul>
                <li>Menu: <strong>Kebidanan → Pemeriksaan Nifas</strong></li>
                <li>Catat kunjungan nifas: kondisi ibu pasca persalinan, involusi uterus, laktasi, dan tanda bahaya.</li>
            </ul>
            <h3>Imunisasi Bayi</h3>
            <ul>
                <li>Menu: <strong>Kebidanan → Imunisasi Bayi</strong></li>
                <li>Jadwal dan pencatatan imunisasi bayi: BCG, DPT, Polio, Hepatitis B, Campak, dll.</li>
            </ul>
        </section>

        {{-- 11. Penunjang Medis --}}
        <section id="penunjang" class="docs-section">
            <span class="docs-badge">Bab 11</span>
            <h2>Penunjang Medis</h2>
            <h3>Laboratorium</h3>
            <ul>
                <li>Menu: <strong>Operasional → Lab Test</strong></li>
                <li>Order pemeriksaan lab dan catat hasilnya: hematologi, kimia darah, urinalisis, mikrobiologi, dll.</li>
                <li>Status: Requested → Sample Collected → In Progress → Completed.</li>
            </ul>
            <h3>Radiologi</h3>
            <ul>
                <li>Menu: <strong>Operasional → Radiologi</strong></li>
                <li>Order pemeriksaan radiologi: X-ray, USG, CT Scan, MRI.</li>
                <li>Upload hasil gambar dan catat interpretasi dokter radiologi.</li>
            </ul>
            <h3>Bank Darah</h3>
            <ul>
                <li>Menu: <strong>Operasional → Bank Darah</strong></li>
                <li>Kelola stok darah per golongan (A, B, AB, O) dan rhesus.</li>
                <li>Catat donasi darah masuk dan permintaan darah keluar.</li>
            </ul>
            <h3>Farmasi</h3>
            <ul>
                <li>Menu: <strong>Farmasi → Obat</strong></li>
                <li>Kelola inventaris obat: nama, kategori, satuan, stok, harga beli, harga jual.</li>
                <li>Tracking stok otomatis berkurang saat resep dibuat.</li>
            </ul>
            <h3>Rawat Inap</h3>
            <ul>
                <li>Menu: <strong>Rawat Inap → Kamar</strong></li>
                <li>Kelola kamar rawat inap: tipe (VVIP, VIP, Kelas 1-3), fasilitas, tarif per malam, status (tersedia/terisi).</li>
            </ul>
            <h3>Ambulans</h3>
            <ul>
                <li>Menu: <strong>Rawat Inap → Ambulans</strong></li>
                <li>Kelola armada ambulans dan catat panggilan ambulans (lokasi jemput, tujuan, status).</li>
            </ul>
            <h3>OK / Bedah</h3>
            <ul>
                <li>Menu: <strong>Operasional → Operasi</strong></li>
                <li>Jadwalkan dan catat operasi: pasien, dokter bedah, jenis operasi, ruang OK, status.</li>
            </ul>
        </section>

        {{-- 12. Keuangan --}}
        <section id="keuangan" class="docs-section">
            <span class="docs-badge">Bab 12</span>
            <h2>Keuangan</h2>
            <h3>Pembayaran</h3>
            <ul>
                <li>Menu: <strong>Keuangan → Pembayaran</strong></li>
                <li>Proses pembayaran untuk setiap transaksi pasien (konsultasi, tindakan, obat, rawat inap).</li>
                <li>Metode pembayaran: Tunai, Transfer Bank, Debit, Kredit, QRIS.</li>
                <li>Invoice otomatis dengan format: <code>INV/YYYY/MM/XXXXX</code>.</li>
            </ul>
            <h3>Laporan</h3>
            <ul>
                <li>Menu: <strong>Keuangan → Laporan</strong></li>
                <li>Laporan pendapatan per periode, per poli, per metode pembayaran.</li>
                <li>Laporan pasien, appointment, dan rekam medis.</li>
                <li>Filter berdasarkan rentang tanggal.</li>
            </ul>
        </section>

        {{-- 13. HR & Payroll --}}
        <section id="hr-payroll" class="docs-section">
            <span class="docs-badge">Bab 13</span>
            <h2>HR & Payroll</h2>
            <h3>Karyawan</h3>
            <ul>
                <li>Menu: <strong>HR → Karyawan</strong></li>
                <li>Data karyawan: NIP, nama, jabatan, departemen, tanggal bergabung, gaji pokok, status (aktif/nonaktif).</li>
            </ul>
            <h3>Absensi</h3>
            <ul>
                <li>Menu: <strong>HR → Absensi</strong></li>
                <li>Catat kehadiran karyawan: jam masuk, jam keluar, status (hadir/izin/sakit/alfa).</li>
            </ul>
            <h3>Penggajian</h3>
            <ul>
                <li>Menu: <strong>HR → Gaji</strong></li>
                <li>Generate gaji karyawan per periode (bulanan).</li>
                <li>Komponen: gaji pokok, tunjangan, potongan, lembur.</li>
                <li>Status: Draft → Approved → Paid.</li>
            </ul>
            <h3>Cuti</h3>
            <ul>
                <li>Menu: <strong>HR → Cuti</strong></li>
                <li>Pengajuan cuti karyawan: tanggal mulai, tanggal selesai, jenis cuti, alasan.</li>
                <li>Status: Pending → Approved → Rejected.</li>
            </ul>
        </section>

        {{-- 14. Akuntansi --}}
        <section id="akuntansi" class="docs-section">
            <span class="docs-badge">Bab 14</span>
            <h2>Akuntansi</h2>
            <h3>Chart of Accounts</h3>
            <ul>
                <li>Menu: <strong>Keuangan → Chart of Accounts</strong></li>
                <li>Buat struktur akun: Aset, Kewajiban, Ekuitas, Pendapatan, Beban.</li>
                <li>Setiap akun memiliki kode unik, nama, tipe, dan saldo normal (Debit/Kredit).</li>
            </ul>
            <h3>Jurnal Umum</h3>
            <ul>
                <li>Menu: <strong>Keuangan → Jurnal Umum</strong></li>
                <li>Buat entri jurnal double-entry dengan memilih akun debit dan kredit.</li>
                <li>Status: Draft → Posted.</li>
                <li>Jurnal yang sudah diposting tidak dapat diubah (immutable).</li>
            </ul>
        </section>

        {{-- 15. Logistik --}}
        <section id="logistik" class="docs-section">
            <span class="docs-badge">Bab 15</span>
            <h2>Logistik</h2>
            <h3>Aset & Inventaris</h3>
            <ul>
                <li>Menu: <strong>Logistik → Aset</strong></li>
                <li>Kelola aset rumah sakit: nama aset, kategori, lokasi, tanggal perolehan, nilai, penyusutan, status.</li>
            </ul>
            <h3>Purchase Order</h3>
            <ul>
                <li>Menu: <strong>Logistik → Purchase Order</strong></li>
                <li>Buat PO untuk pengadaan barang/obat: supplier, item, jumlah, harga.</li>
                <li>Status: Draft → Sent → Approved → Received.</li>
            </ul>
        </section>

        {{-- 16. Pengaturan --}}
        <section id="pengaturan" class="docs-section">
            <span class="docs-badge">Bab 16</span>
            <h2>Pengaturan</h2>
            <h3>Branding & Whitelabel</h3>
            <ul>
                <li>Menu: <strong>Sistem → Branding</strong></li>
                <li>Sesuaikan identitas aplikasi: nama aplikasi, logo, favicon, footer text, hero title/subtitle.</li>
                <li>Preview real-time di panel sebelah kanan.</li>
            </ul>
            <h3>Satu Sehat (SATUSEHAT)</h3>
            <ul>
                <li>Menu: <strong>Sistem → Satu Sehat</strong></li>
                <li>Konfigurasi integrasi ke platform SATUSEHAT Kemenkes.</li>
                <li>Isi: Base URL, Client ID, Client Secret, Organization ID, dan status enable/disable.</li>
            </ul>
            <h3>BPJS Kesehatan</h3>
            <ul>
                <li>Menu: <strong>Sistem → BPJS</strong></li>
                <li>Konfigurasi koneksi ke BPJS Kesehatan: Base URL, Consumer ID, Consumer Secret, User Key.</li>
            </ul>
            <h3>CMS Tampilan</h3>
            <ul>
                <li>Menu: <strong>Sistem → CMS Tampilan</strong></li>
                <li>Edit konten landing page: hero section, features, about, CTA.</li>
                <li>Preview langsung sebelum publish.</li>
            </ul>
            <h3>Pengaturan Umum</h3>
            <ul>
                <li>Menu: <strong>Sistem → Pengaturan</strong></li>
                <li>Konfigurasi umum sistem (tersedia bertahap).</li>
            </ul>
        </section>

        {{-- 17. Screenshot fitur real dari aplikasi --}}
        <section id="screenshot-fitur" class="docs-section">
            <span class="docs-badge">Bab 17</span>
            <h2>Screenshot Fitur Real</h2>
            <p>Seluruh gambar berikut diambil dari aplikasi yang berjalan menggunakan data demo. Gunakan bagian ini sebagai orientasi sebelum masuk ke modul masing-masing.</p>
            @php
                $docsFeatures = [
                    ['file' => 'dashboard.png', 'url' => '/dashboard', 'title' => 'Dashboard Eksekutif', 'description' => 'Pantau indikator penting rumah sakit dari satu layar.', 'bullets' => ['KPI pasien, dokter, appointment, dan pendapatan', 'Okupansi kamar dan stok obat', 'Grafik kunjungan dan poli terpadat', 'Peringatan operasional yang perlu ditindaklanjuti']],
                    ['file' => 'appointments.png', 'url' => '/appointments', 'title' => 'Appointment & Antrian', 'description' => 'Atur jadwal kunjungan agar petugas dan dokter bekerja dengan konteks yang sama.', 'bullets' => ['Filter tanggal dan status kunjungan', 'Relasi pasien, dokter, poli, dan tindakan', 'Status scheduled hingga completed', 'Akses cepat ke detail pasien']],
                    ['file' => 'medical-records.png', 'url' => '/medical-records', 'title' => 'Rekam Medis Digital', 'description' => 'Catat perjalanan klinis pasien secara terstruktur dan mudah ditelusuri.', 'bullets' => ['Diagnosis dan kode ICD-10', 'Tindakan dan resep terhubung', 'Riwayat vital signs dan hasil penunjang', 'Soft delete untuk menjaga jejak data']],
                    ['file' => 'drugs.png', 'url' => '/drugs', 'title' => 'Farmasi & Stok Obat', 'description' => 'Kurangi risiko kekosongan obat dengan visibilitas stok yang jelas.', 'bullets' => ['Stok, satuan, kategori, dan harga', 'Penanda stok rendah', 'Pencarian obat cepat', 'Terhubung dengan resep dan procurement']],
                    ['file' => 'emergencies.png', 'url' => '/emergencies', 'title' => 'IGD & Triase', 'description' => 'Prioritaskan pasien gawat darurat dengan status triase yang mudah dipantau.', 'bullets' => ['Kategori triase merah hingga hitam', 'Status waiting, treatment, observation', 'Catatan diagnosis dan tindakan', 'Dokumen cetak triase']],
                    ['file' => 'lab-tests.png', 'url' => '/lab-tests', 'title' => 'Laboratorium', 'description' => 'Kelola permintaan dan hasil pemeriksaan penunjang dalam alur yang terhubung.', 'bullets' => ['Jenis pemeriksaan dan sample type', 'Status proses pemeriksaan', 'Hasil dan tanggal hasil', 'Relasi dokter serta pasien']],
                    ['file' => 'payments.png', 'url' => '/payments', 'title' => 'Pembayaran & Invoice', 'description' => 'Lacak transaksi dan status pembayaran untuk kebutuhan kasir serta audit.', 'bullets' => ['Nomor invoice dan penjamin', 'Status pending hingga completed', 'Cetak kuitansi dan tagihan', 'Relasi appointment dan rekam medis']],
                    ['file' => 'journal-entries.png', 'url' => '/journal-entries', 'title' => 'Akuntansi Jurnal', 'description' => 'Jaga pembukuan tetap seimbang dengan jurnal double-entry.', 'bullets' => ['Chart of accounts', 'Debit dan kredit per baris jurnal', 'Validasi keseimbangan jurnal', 'Status draft dan posted']],
                ];
            @endphp
            @foreach($docsFeatures as $feature)
                <div class="docs-shot-row {{ $loop->even ? 'reverse' : '' }}">
                    <div class="docs-shot-frame"><div class="docs-shot-chrome"><span></span><span></span><span></span><div class="docs-shot-url">SIMRS Hospital {{ $feature['url'] }}</div></div><img src="{{ asset('marketing/screens/'.$feature['file']) }}" alt="Screenshot {{ $feature['title'] }}" loading="lazy"></div>
                    <div class="docs-shot-copy"><h3>{{ $feature['title'] }}</h3><p>{{ $feature['description'] }}</p><ul>@foreach($feature['bullets'] as $bullet)<li>{{ $bullet }}</li>@endforeach</ul></div>
                </div>
            @endforeach
        </section>

        {{-- 18. FAQ --}}
        <section id="faq" class="docs-section">
            <span class="docs-badge">Bab 18</span>
            <h2>FAQ — Pertanyaan Umum</h2>

            <h3>Bagaimana cara reset password?</h3>
            <p>Hubungi administrator sistem untuk mereset password. Fitur self-service reset password akan tersedia di update mendatang.</p>

            <h3>Apakah sistem bisa diakses dari HP?</h3>
            <p>Ya. Sistem ini fully responsive dan bisa diakses dari smartphone, tablet, maupun desktop.</p>

            <h3>Bagaimana cara backup data?</h3>
            <p>Backup database dapat dilakukan melalui server hosting (cPanel/plesk) atau command line MySQL dump. Disarankan backup rutin setiap hari.</p>

            <h3>Apakah mendukung BPJS dan SATUSEHAT?</h3>
            <p>Ya. Sistem menyediakan modul integrasi untuk BPJS Kesehatan dan SATUSEHAT Kemenkes. Konfigurasi dapat diatur di menu <strong>Sistem → BPJS</strong> dan <strong>Sistem → Satu Sehat</strong>.</p>

            <h3>Berapa banyak user yang bisa dibuat?</h3>
            <p>Tidak ada batasan jumlah user. Anda bisa membuat user sebanyak yang dibutuhkan dengan 7 role berbeda.</p>

            <h3>Bagaimana jika ada bug atau butuh bantuan?</h3>
            <p>Hubungi tim support melalui WhatsApp di nomor yang tercantum di halaman utama aplikasi. Kami siap membantu instalasi dan troubleshooting.</p>

            <h3>Apakah ada biaya berlangganan?</h3>
            <p>Sistem ini menggunakan lisensi one-time purchase. Setelah pembelian source code, Anda bebas menggunakannya tanpa biaya bulanan.</p>

            <h3>Bisa custom fitur tambahan?</h3>
            <p>Ya. Karena Anda memiliki source code lengkap, Anda bisa melakukan kustomisasi sesuai kebutuhan. Tim kami juga tersedia untuk jasa kustomisasi jika diperlukan.</p>
        </section>

    </main>
</div>

<footer style="text-align:center;padding:2rem 1.5rem;color:var(--gray-400);font-size:0.8rem;border-top:1px solid var(--gray-200);">
    <span>&copy; {{ date('Y') }} {{ config('app.name', 'SIRS') }}. All rights reserved.</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    const links = document.querySelectorAll('.docs-sidebar a');
    const sections = document.querySelectorAll('.docs-section');

    function setActive() {
        let current = '';
        sections.forEach(section => {
            const top = section.getBoundingClientRect().top;
            if (top < 150) current = section.getAttribute('id');
        });
        links.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) link.classList.add('active');
        });
    }

    window.addEventListener('scroll', setActive);
    setActive();
})();
</script>

</body>
</html>

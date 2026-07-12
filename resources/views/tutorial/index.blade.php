@extends('layouts.admin')

@section('title', 'Tutorial Penggunaan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header mb-4">
    <h1 class="h2">📚 Tutorial Penggunaan</h1>
    <span class="text-muted small">{{ now()->format('d M Y') }}</span>
</div>

<div class="row g-3">

    {{-- 1. Dashboard --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-speedometer2 text-primary me-2"></i> 1. Dashboard</span>
                <span class="badge bg-primary">Start Here</span>
            </div>
            <div class="card-body small text-muted">
                Pusat kendali rumah sakit. Menampilkan 6 stat cards real-time: total pasien, dokter aktif, appointment hari ini, pendapatan bulan ini, stok obat, dan ketersediaan kamar. Di bawahnya ada tabel appointment terbaru dan pembayaran terbaru untuk monitoring cepat. Gunakan dark mode toggle di pojok kanan atas untuk kenyamanan mata.
            </div>
        </div>
    </div>

    {{-- 2. Pasien --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-people text-success me-2"></i> 2. Pasien</span>
                <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Kelola data pasien: NIK (16 digit), No. BPJS, NIK verified status, nama, jenis kelamin, tanggal lahir, golongan darah, alamat, alergi, riwayat medis, kontak darurat. Setiap pasien terhubung ke appointment, rekam medis, lab, radiologi, operasi, IGD, dan bersalin. Gunakan search bar untuk mencari berdasarkan nama/NIK/telepon.
            </div>
        </div>
    </div>

    {{-- 3. Dokter --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-person-badge text-info me-2"></i> 3. Dokter</span>
                <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Data dokter lengkap: spesialisasi, STR, biaya konsultasi, status (aktif/cuti/tidak aktif). Setiap dokter bisa di-assign ke 1-2 poli melalui modul Poli. Dokter terhubung ke appointment, rekam medis, operasi, lab, radiologi, dan rujukan. Filter berdasarkan status atau cari berdasarkan nama/spesialisasi.
            </div>
        </div>
    </div>

    {{-- 4. Pengguna --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-shield-check text-danger me-2"></i> 4. Pengguna</span>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Manajemen akun pengguna dengan 9 role: <b>Admin</b> (akses penuh), <b>Doctor</b> (akses poli), <b>Staff</b> (administrasi), <b>Nurse</b> (perawat), <b>Midwife</b> (bidan), <b>Pharmacist</b> (farmasi), <b>Cashier</b> (kasir), <b>Lab Technician</b> (laboratorium). Atur username, email, password. Role-based access control membatasi akses sesuai tanggung jawab.
            </div>
        </div>
    </div>

    {{-- 5. Jadwal Staff --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-calendar-week text-purple me-2"></i> 5. Jadwal Staff</span>
                <a href="{{ route('staff-schedules.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Atur roster/shift seluruh staff rumah sakit. Pilih user dan atur shift <b>Pagi</b> (07:00-14:00), <b>Siang</b> (14:00-21:00), atau <b>Malam</b> (21:00-07:00) per tanggal. Filter berdasarkan user, tanggal, atau department. Cocok untuk perencanaan SDM mingguan/bulanan.
            </div>
        </div>
    </div>

    {{-- 6. Treatment --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-capsule text-warning me-2"></i> 6. Treatment</span>
                <a href="{{ route('treatments.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Katalog tindakan medis lengkap. Setiap treatment memiliki: nama, kategori (Pemeriksaan, Konsultasi, Laboratorium, Radiologi, Operasi, Terapi, Rawat Inap), harga, durasi (menit), dan persyaratan (ditulis per baris). Treatment bisa diaktifkan/nonaktifkan. Slug auto-generate dari nama. Filter berdasarkan kategori.
            </div>
        </div>
    </div>

    {{-- 7. Appointment --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-calendar-check text-primary me-2"></i> 7. Appointment</span>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Jadwalkan janji temu pasien-dokter. Pilih pasien, dokter, treatment (opsional), tanggal, jam mulai, dan keluhan. Status workflow: <b>scheduled → confirmed → in_progress → completed</b> (atau cancelled/no_show). Quick status change via tombol. Filter by status, tanggal, atau search pasien/dokter. Reminders dalam format JSON.
            </div>
        </div>
    </div>

    {{-- 8. Rekam Medis --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-file-medical text-success me-2"></i> 8. Rekam Medis</span>
                <a href="{{ route('medical-records.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Catat rekam medis digital untuk setiap pasien. Isi: diagnosis, tindakan (action), obat/resep (medicine), vital signs (tekanan darah, detak jantung, suhu, berat, tinggi — dalam format JSON), hasil lab, dan catatan. Terhubung ke appointment. Soft delete aman. Cari berdasarkan nama pasien atau diagnosis.
            </div>
        </div>
    </div>

    {{-- 9. Poli & Antrian --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-clipboard2-pulse text-info me-2"></i> 9. Poli & Antrian</span>
                <a href="{{ route('polyclinics.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Kelola 12 poli/klinik (Umum, Gigi, Jantung, Saraf, Mata, THT, Kulit, Anak, Obgyn, Bedah, Orthopedi, Penyakit Dalam). Setiap poli punya kode, lantai, telepon, dan dokter yang di-assign. Modul <b>Antrian</b> menghasilkan nomor otomatis format <code>{KODE}-001</code>. Status antrian: waiting → called → in_progress → completed. Tombol "Panggil" dan "Selesai" untuk workflow cepat.
            </div>
        </div>
    </div>

    {{-- 10. Rujukan --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-arrow-left-right text-danger me-2"></i> 10. Rujukan</span>
                <a href="{{ route('referrals.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Sistem rujukan antar poli. Rujuk pasien dari Poli A ke Poli B dengan alasan dan diagnosis. Status: pending → approved → rejected → completed. Terhubung ke pasien, dokter, dan poli asal/tujuan. Cocok untuk eskalasi kasus dari poli umum ke spesialis.
            </div>
        </div>
    </div>

    {{-- 11. IGD --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-lightning-charge text-danger me-2"></i> 11. IGD / Emergency</span>
                <a href="{{ route('emergencies.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Modul Instalasi Gawat Darurat. Setiap pasien IGD memiliki <b>triase</b>: Merah (critical), Kuning (urgent), Hijau (non-urgent), Hitam (deceased). Catat mode kedatangan (Ambulans/Sendiri/Rujukan), keluhan, diagnosis, tindakan, dan status (waiting→in_treatment→observation→discharged/referred/deceased). Quick add via Quick Actions sidebar.
            </div>
        </div>
    </div>

    {{-- 12. Ruang Bersalin --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-hearts text-pink me-2"></i> 12. Ruang Bersalin</span>
                <a href="{{ route('maternities.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Catat persalinan pasien. Isi: tanggal masuk, tanggal lahir, metode persalinan (Normal/Caesar/Vacuum/Forceps), data bayi (nama, gender, berat, panjang), komplikasi, dan status (admitted→in_labor→delivered→postpartum→discharged). Pilih pasien perempuan dan dokter Obgyn/Umum.
            </div>
        </div>
    </div>

    {{-- 13. Perawat & Bidan --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-clipboard-heart text-pink me-2"></i> 13. Perawat & Bidan</span>
                <a href="{{ route('nurse-assignments.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Penugasan perawat dan bidan ke pasien. Pilih pasien, petugas (nurse/midwife), tipe penugasan, deskripsi tugas (misal: "Cek tekanan darah setiap 4 jam", "Periksa pembukaan"), shift (pagi/siang/malam). Status: pending → in_progress → completed. Filter berdasarkan tipe, status, atau petugas.
            </div>
        </div>
    </div>

    {{-- 14. Laboratorium --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-droplet text-primary me-2"></i> 14. Laboratorium</span>
                <a href="{{ route('lab-tests.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Pemeriksaan laboratorium lengkap. Tipe: Hematologi, Kimia Darah, Urinalisis, Serologi, Mikrobiologi, Imunologi. Jenis sampel: Darah, Urine, Swab, Feses. Workflow: requested → sample_collected → in_progress → completed. Isi hasil dan tanggal hasil saat selesai. Filter by status, tipe, atau pasien.
            </div>
        </div>
    </div>

    {{-- 15. Radiologi --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-c-circle text-info me-2"></i> 15. Radiologi</span>
                <a href="{{ route('radiologies.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Pemeriksaan radiologi dan pencitraan. Jenis: Thorax, Abdomen, CT Scan Kepala/Abdomen, MRI, USG Abdomen/Kehamilan, Mamografi, Bone Survey. Catat bagian tubuh yang diperiksa, temuan (findings), dan status (requested→in_progress→completed). Filter by status atau search pasien.
            </div>
        </div>
    </div>

    {{-- 16. Bank Darah --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-droplet-fill text-danger me-2"></i> 16. Bank Darah</span>
                <a href="{{ route('blood-donations.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Manajemen stok darah. Catat donor: nama, golongan darah (A/B/AB/O), rhesus (+/-), jumlah (ml), tanggal donor, dan nomor kantong. Expiry otomatis: donation_date + 42 hari. Status: available → reserved → used → expired → discarded. Bisa di-assign ke pasien tertentu. Filter by golongan darah atau status.
            </div>
        </div>
    </div>

    {{-- 17. Farmasi --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-capsule-pill text-warning me-2"></i> 17. Farmasi</span>
                <a href="{{ route('drugs.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Inventaris obat-obatan. 20 obat seeded dengan nama Indonesia (Paracetamol, Amoxicillin, Omeprazole, dll). Data: kategori (Tablet/Sirup/Salep/Injeksi/Kapsul), satuan (Strip/Botol/Ampul/Tube), stok, harga, deskripsi. Status otomatis berdasarkan stok: Aktif (stok > 0), Habis (stok = 0). Filter by kategori.
            </div>
        </div>
    </div>

    {{-- 18. Rawat Inap --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-building text-info me-2"></i> 18. Rawat Inap</span>
                <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Manajemen 15 kamar rawat inap: 2 VIP, 4 Kelas 1, 5 Kelas 2, 4 Kelas 3. Data: nomor kamar, tipe, lantai, jumlah tempat tidur, harga per hari, fasilitas (AC, TV, Kamar Mandi — per baris), status (tersedia/terisi/perbaikan). Filter by status atau search nomor kamar.
            </div>
        </div>
    </div>

    {{-- 19. Operasi --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-scissors text-purple me-2"></i> 19. Operasi / OT</span>
                <a href="{{ route('surgeries.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Manajemen kamar operasi (Operating Theater). Catat: nama operasi, tipe (elektif/emergency/minor/major), OT room, tanggal jadwal, waktu mulai/selesai, tipe anestesi (General/Regional/Local), komplikasi. Status: scheduled → pre_op → in_progress → post_op → completed. Filter by status atau tanggal.
            </div>
        </div>
    </div>

    {{-- 20. Ambulans --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-truck text-danger me-2"></i> 20. Ambulans</span>
                <a href="{{ route('ambulances.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Kelola armada ambulans (5 unit). Data: nomor kendaraan, nama supir, telepon supir, status (tersedia/bertugas/perbaikan). Modul <b>Panggilan Darurat</b> mencatat dispatch: nama pasien, telepon, alamat penjemputan, tujuan, waktu panggilan. Status: pending → dispatched → arrived → picked_up → completed.
            </div>
        </div>
    </div>

    {{-- 21. Pembayaran --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-cash-stack text-success me-2"></i> 21. Pembayaran</span>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Catat dan lacak pembayaran pasien. Invoice otomatis format <code>INV/YYYY/MM/XXXXX</code>. Metode: Tunai, Transfer, Debit, Kredit, QRIS, Asuransi. Perhitungan: subtotal - discount + tax = amount. paid_amount - amount = change. Status: pending → completed → cancelled → refunded. Filter by status, metode, atau tanggal.
            </div>
        </div>
    </div>

    {{-- 22. Laporan --}}
    <div class="col-lg-6">
        <div class="card card-panel h-100">
            <div class="card-header">
                <span><i class="bi bi-bar-chart text-danger me-2"></i> 22. Laporan</span>
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Buka</a>
            </div>
            <div class="card-body small text-muted">
                Dashboard laporan komprehensif. 6 stat cards: total pasien, dokter, appointment, pembayaran, obat, kamar. Tabel pendapatan per bulan. Appointment by status breakdown. Top 5 treatment terlaris. 10 pembayaran terbaru. Gunakan untuk rapat manajemen dan evaluasi kinerja rumah sakit.
            </div>
        </div>
    </div>

</div>
@endsection

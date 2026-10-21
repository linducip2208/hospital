# KNOWN-LIMITATIONS (jujur, per 2026-10-21)

# KNOWN-LIMITATIONS (jujur, per 2026-10-21, refresh kedua)

1. **RBAC web granular — struktur + matriks selesai, butuh pengesahan pemilik.** `RequireResourcePermission` (fail-open bila permission belum di-seed) + `config/resource_permissions.php` + hibah seeder per peran aktif dan teruji (`ResourcePermissionTest` 3/3). Sisa: (a) jalankan `php artisan db:seed --class=PermissionSeeder` di produksi untuk mengaktifkan; (b) pemilik meninjau matriks manage per modul di config. Risiko sisa: matriks adalah keputusan operasional terbaik-menurut-audit, bukan SK formal RS. (P1, mitigasi terpasang)
2. **7 tabel tanpa SoftDeletes** — `IcuMonitoring, MedicationAdministration, NurseAssignment, Partograph, Queue, ShiftHandover, VitalSignsRecord`: hapus = permanen. Tidak diubah karena butuh keputusan retensi pemilik data + migrasi schema. (P1, terbuka)
3. **Integrasi live belum terverifikasi** — SatuSehat/BPJS/SMTP/pembayaran eksternal: hanya review kode + `satusehat:diagnose` (butuh kredensial + server). Status NOT TESTED. (P0 potensial, butuh UAT)
4. **Restore backup belum diuji end-to-end** — prosedur sesuai kode (`db:backup`, cron, retensi 30 hari); tidak ada environment isolasi saat audit. (P1)
5. **Load/stress test tidak dijalankan** — tidak ada klaim SLA. (P2)
6. **Lisensi API** — grup `api` dicek pairing (JSON 403). Instalasi yang sudah pairing tidak terdampak; instalasi belum pairing terblokir juga via API (disengaja). Webhook SatuSehat ikut aturan yang sama.
7. **Data lama `paid/partial`** — bila migrasi 2026_04_30 sudah jalan sebelum audit, nilai lama MySQL kemungkinan sudah hilang/tercoerce; normalisasi baru hanya menangani sisa. Tidak dapat dipulihkan dari kode.
8. **Aturan pajak/diskon** — diskon kini dibatasi ≤ subtotal (API); tidak ada tabel tarif pajak — perubahan kebijakan tarif = keputusan pemilik RS. Batas diskon per peran (mis. kasir maks Rp X) belum didefinisikan bisnis → belum diimplementasikan.
2. **7 tabel tanpa SoftDeletes** — `IcuMonitoring, MedicationAdministration, NurseAssignment, Partograph, Queue, ShiftHandover, VitalSignsRecord`: hapus = permanen. Tidak diubah karena butuh keputusan retensi pemilik data + migrasi schema. (P1, terbuka)
3. **Integrasi live belum terverifikasi** — SatuSehat/BPJS/SMTP/pembayaran eksternal: hanya review kode + `satusehat:diagnose` (butuh kredensial + server). Status NOT TESTED. (P0 potensial, butuh UAT)
4. **Restore backup belum diuji end-to-end** — prosedur sesuai kode (`db:backup`, cron, retensi 30 hari); tidak ada environment isolasi saat audit. (P1)
5. **Load/stress test tidak dijalankan** — tidak ada klaim SLA. (P2)
6. **Lisensi API** — grup `api` kini dicek pairing (JSON 403). Instalasi yang sudah pairing tidak terdampak; instalasi belum pairing akan terblokir juga via API (disengaja). Webhook SatuSehat ikut aturan yang sama.
7. **Data lama `paid/partial`** — bila migrasi 2026_04_30 sudah jalan sebelum audit, nilai lama MySQL kemungkinan sudah hilang/tercoerce; normalisasi baru hanya menangani sisa. Tidak dapat dipulihkan dari kode.
8. **Aturan pajak/diskon** — mengikuti implementasi existing (`discount/tax` dari input kasir, tanpa tabel tarif pajak). Perubahan kebijakan tarif = keputusan pemilik RS, bukan audit ini.

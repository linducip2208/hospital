# KNOWN-LIMITATIONS (jujur, per 2026-10-21)

1. **RBAC web belum granular** — ±97 controller CRUD hanya dijaga `auth`; `permission:` hanya di ~19 aksi. Mitigasi: API sudah dikunci per-peran untuk modul sensitif; web tetap mengikuti perilaku lama agar tidak merusak operasional. Rencana: tambah `permission:` per resource bertahap + seeder permission baru. Risiko: user login apa pun bisa akses menu yang terlihat. (P1, terbuka)
2. **7 tabel tanpa SoftDeletes** — `IcuMonitoring, MedicationAdministration, NurseAssignment, Partograph, Queue, ShiftHandover, VitalSignsRecord`: hapus = permanen. Tidak diubah karena butuh keputusan retensi pemilik data + migrasi schema. (P1, terbuka)
3. **Integrasi live belum terverifikasi** — SatuSehat/BPJS/SMTP/pembayaran eksternal: hanya review kode + `satusehat:diagnose` (butuh kredensial + server). Status NOT TESTED. (P0 potensial, butuh UAT)
4. **Restore backup belum diuji end-to-end** — prosedur sesuai kode (`db:backup`, cron, retensi 30 hari); tidak ada environment isolasi saat audit. (P1)
5. **Load/stress test tidak dijalankan** — tidak ada klaim SLA. (P2)
6. **Lisensi API** — grup `api` kini dicek pairing (JSON 403). Instalasi yang sudah pairing tidak terdampak; instalasi belum pairing akan terblokir juga via API (disengaja). Webhook SatuSehat ikut aturan yang sama.
7. **Data lama `paid/partial`** — bila migrasi 2026_04_30 sudah jalan sebelum audit, nilai lama MySQL kemungkinan sudah hilang/tercoerce; normalisasi baru hanya menangani sisa. Tidak dapat dipulihkan dari kode.
8. **Aturan pajak/diskon** — mengikuti implementasi existing (`discount/tax` dari input kasir, tanpa tabel tarif pajak). Perubahan kebijakan tarif = keputusan pemilik RS, bukan audit ini.

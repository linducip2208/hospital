# Patient Journey Terintegrasi

1. Registrasi pasien menghasilkan MRN dan data payer.
2. Appointment mengikat pasien, dokter, poli, dan treatment.
3. Appointment confirmed membentuk satu encounter; queue hanya mereferensikan encounter tersebut.
4. Pemanggilan antrian membuka encounter `in_progress`.
5. Screening, vital, catatan dokter, diagnosis ICD-10, tindakan, dan order penunjang memakai encounter yang sama.
6. Hasil lab/radiologi diverifikasi sebelum dianggap selesai; critical result menjadi event notifikasi.
7. Prescription diteruskan ke farmasi. Hanya dispensing yang mengurangi stok.
8. Setiap layanan menghasilkan charge. Billing menerbitkan bill/invoice; pembayaran dialokasikan.
9. Rawat inap menambahkan admission dan bed movement. Discharge memeriksa summary finalized dan final billing.
10. Payment/refund diposting ke ledger secara idempotent. Encounter kemudian completed/closed.

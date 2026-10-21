# DATABASE-CHANGES (audit 2026-10-21)

## Migrasi baru (kompatibel, rollback tersedia)

1. `2026_10_21_000001_add_expiry_and_patient_tokens`
   - `api_tokens.expires_at nullable` (token lama tetap valid).
   - Tabel baru `patient_api_tokens` (FK patients cascade, token_hash unique).
   - Rollback: drop tabel + drop kolom.
2. `2026_10_21_000002_normalize_legacy_payment_status`
   - Update data idempoten: `paid→completed`, `partial→pending`.
   - Latar: migrasi lama `2026_04_30_071057` drop kolom status (potensi data loss nilai lama di MySQL). Tidak mengubah migrasi lama; tidak bisa memulihkan data yang sudah hilang — hanya menormalkan sisa.
   - Rollback: no-op yang aman (by design satu arah).

## Tidak ada perubahan skema lain dalam audit ini. Risiko: RENDAH.
- Semua kolom baru nullable; tidak ada drop tabel/kolom, tidak ada ubah tipe.
- Upgrade: `php artisan migrate --force` biasa. Rollback: `php artisan migrate:rollback --step=2` (kehilangan token pasien bila di-rollback — terima sebagai konsekuensi yang terdokumentasi).

## Catatan integritas
- Unique: `payments.invoice_number`, `bills.bill_no`, `invoices.invoice_number`, `payment_allocations[ payment_id,bill_id]`, `journal[source_type,source_id]`, `document_sequences.sequence_key` — anti duplikat & idempoten (terverifikasi kode).
- Transaksi: BillingService, RefundService, JournalService, DocumentNumberService, receivePayment — semua `DB::transaction` + `lockForUpdate` pada jalur bill. Jalur legacy standalone kini juga transaksi + jurnal (best-effort).
- SoftDeletes: 58/93 model. 7 model klinis/operasional tanpa SoftDeletes (lihat KNOWN-LIMITATIONS) — disengaja tidak diubah (butuh keputusan pemilik data).

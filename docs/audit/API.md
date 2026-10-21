# API — kontrak untuk Flutter (ringkasan audit)

Base: `https://domain-anda.id/api`. Full daftar route: `php artisan route:list --path=api`.

## Auth staf — `POST /auth/tokens` {email,password,name,expires_in_days?}
→ `201 {token, token_type: Bearer, expires_at, user}`. Header: `Authorization: Bearer <token>`.
`POST /auth/tokens/refresh` (Bearer masih berlaku → token baru, lama dihapus).
`DELETE /auth/tokens/{id}` (milik sendiri). Token kedaluwarsa (default 30 hari, maks 90) → 401.

## Auth pasien — `POST /auth/patients/tokens`, `POST /auth/patients/register`
{name,email,phone?,password(+confirmed saat register)} → `{token, patient}`.
`DELETE /v1/auth/patients/tokens/current` untuk cabut. Akun nonaktif → 403/401.

## Staf `/v1/*` (butuh token staf)
- Khusus: `patients doctors treatments appointments(+calendar) medical-records payments`, `POST payments/{id}/refund`.
- Generik 54 modul: `GET/POST {modul}`, `GET/PUT/PATCH/DELETE {modul}/{id}` — `?search=&per_page=`, validasi dari skema DB.
- Batas: hapus default admin/developer (kec. `queues`, `ambulance-calls`); salaries/employees/journal/coa/bills/invoices dibatasi peran; pasien → 403.

## Mandiri pasien `/v1/me/*` (token pasien, hanya milik sendiri)
`GET /`, `GET/POST appointments`, `GET appointments/{id}`, `GET medical-records[/{id}]`, `GET invoices[/{id}]`. Cross-patient → 403.

## Publik tanpa auth (throttle 30/5 per menit)
`GET v1/public/polyclinics|doctors|queue-status`, `POST v1/public/appointments|ambulance-calls|patient-feedbacks`.
Respons disaring (tanpa email/telepon dokter, tanpa nama pasien di antrean). Booking tamu membuat/mencocokkan pasien otomatis, status paksa `scheduled`.

## Error & kompatibilitas
- Format konsisten: sukses JSON resource/pagination; gagal `{message}` (+`error` detail DB yang sudah disamarkan). Status: 200/201/204/401/403/404/422; 500 tanpa stack trace di production.
- Kontrak kompatibel mundur: endpoint lama tidak diubah formatnya; penambahan: `expires_at`, `/refund`, `/public/*`, `DELETE patients tokens/current`, penolakan status langsung (422) — klien lama yang mengandalkan set-status-refunded langsung akan menerima 422 (terdokumentasi, disengaja demi integritas).

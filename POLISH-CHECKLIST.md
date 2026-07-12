# Polish Content Checklist — Sebelum Demo ke Klien

Login sebagai **developer** (`developer@hospital.test` / `password`) → buka **CMS Tampilan** di sidebar.

Edit 14 section di `/cms` untuk sesuaikan dengan brand klien:

## ✅ Branding (Wajib Pertama)
- [ ] **Title** → ganti ke nama RS klien (mis. "RS Sehat Sentosa")
- [ ] **Subtitle** → tagline RS (mis. "Pelayanan Kesehatan Terbaik di Surabaya")
- [ ] **Upload Logo** → drag-drop file logo ke dropzone, crop sesuai kebutuhan
- [ ] **Meta JSON** → `whatsapp_number` ke nomor WA marketing klien

## ✅ Hero
- [ ] **Title** → headline utama (perhatikan `meta.highlight_words` untuk gradient text)
- [ ] **Content** → paragraph deskripsi singkat
- [ ] **Image** → upload foto RS klien (eksterior, ruangan, tim medis)
- [ ] **Button text/url** → CTA primary (mis. "Reservasi Online")

## ✅ Video Demo
- [ ] **Video URL** → ganti YouTube ID dengan video promo klien
- [ ] **Image (poster)** → frame video atau ilustrasi

## ✅ Modules (13 modul)
- [ ] Edit `meta.items` di JSON editor — sesuaikan modul yang RS klien beli
- [ ] Hapus modul yang tidak dibeli, sesuaikan deskripsi

## ✅ Features
- [ ] Edit 8 fitur unggulan jika perlu sesuaikan dengan paket klien

## ✅ Showcase (Screenshot Gallery)
- [ ] Ganti 3 gambar dengan screenshot real produk yang sudah di-customize untuk klien

## ✅ Stats Banner
- [ ] Auto-fetch dari DB sudah aktif (`auto:patients`, `auto:doctors`, `auto:polys`)
- [ ] Sesuaikan label kalau perlu

## ✅ Testimonials
- [ ] Ganti 3 quote dengan testimoni real dari user RS klien
- [ ] Upload foto avatar real (atau pakai inisial)

## ✅ Insights / Blog
- [ ] Ganti 3 artikel placeholder dengan artikel real dari blog klien (atau hide kalau belum ada blog)

## ✅ Partners
- [ ] Edit `meta.items` — list nama partner/asuransi/vendor yang kerjasama dengan RS klien

## ✅ CTA Final
- [ ] Sesuaikan pesan & WhatsApp number

## ✅ Footer
- [ ] **Address** → alamat kantor RS klien
- [ ] **Phone** → nomor telepon utama
- [ ] **Email** → email kontak resmi
- [ ] **Social media** → ganti URL Instagram, LinkedIn, FB, WA, YouTube ke akun klien

## Final Pre-Demo Check
- [ ] Buka `/` di browser tab incognito — test semua section render benar
- [ ] Test responsive di mobile (Chrome DevTools)
- [ ] Test PWA install di Chrome desktop & mobile
- [ ] Test floating WhatsApp chat (klik harus buka WA dengan pesan pre-filled)
- [ ] Test video modal play
- [ ] Test login `developer@hospital.test` / `password` (atau ganti password sebelum demo!)
- [ ] Klik beberapa menu sidebar — pastikan tidak ada error 500

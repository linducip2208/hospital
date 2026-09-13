# Security

- Production `.env.example` memakai `APP_DEBUG=false`; credential integrasi terenkripsi.
- API memakai bearer token hash, rate limit token issuance, pagination, dan payload pasien disaring untuk role non-klinis.
- CSRF aktif pada web; validasi allow-list; mass assignment memakai fillable.
- Webhook SatuSehat wajib signature valid; log tidak berisi token, password, secret, NIK, atau nama pasien.
- Clinical record finalized tidak dapat diedit biasa.
- Foreign key medical history memakai restrict/nullOnDelete; record utama memakai soft delete.
- Production wajib HTTPS, cookie secure, backup terenkripsi, queue worker terpisah, rate limit login, dan secret rotation.
- Header `nosniff`, frame protection, referrer policy, permissions policy, HSTS production, dan no-store untuk response authenticated diterapkan global.
- `/health/ready` tidak membocorkan detail koneksi; endpoint hanya mengembalikan status database, cache, dan storage.
- API session auth hanya aktif untuk local/testing atau jika `API_ALLOW_SESSION=true`; production harus memakai bearer token.

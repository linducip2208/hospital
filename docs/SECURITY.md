# Security

- Production `.env.example` memakai `APP_DEBUG=false`; credential integrasi terenkripsi.
- API memakai bearer token hash, rate limit token issuance, pagination, dan payload pasien disaring untuk role non-klinis.
- CSRF aktif pada web; validasi allow-list; mass assignment memakai fillable.
- Webhook SatuSehat wajib signature valid; log tidak berisi token, password, secret, NIK, atau nama pasien.
- Clinical record finalized tidak dapat diedit biasa.
- Foreign key medical history memakai restrict/nullOnDelete; record utama memakai soft delete.
- Production wajib HTTPS, cookie secure, backup terenkripsi, queue worker terpisah, rate limit login, dan secret rotation.

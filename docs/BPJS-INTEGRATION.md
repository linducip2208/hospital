# BPJS / VClaim

- **Simulation / Not connected to BPJS**: validasi lokal nomor 13 digit dan status NIK; bukan eligibility BPJS nyata.
- **Live**: `BpjsVClaimGateway` memakai base URL dan credential Settings terenkripsi, timeout/retry terbatas, signature request, serta tidak menulis credential/response PHI ke log.

Tanpa konfigurasi lengkap sistem memakai `LocalInsuranceGateway`. Live error tidak diam-diam difallback ke simulasi. Eligibility, SEP, referral, claim status, dan coverage lanjutan memerlukan UAT dengan endpoint resmi.

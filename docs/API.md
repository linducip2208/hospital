# API v1

```http
POST /api/auth/tokens
Content-Type: application/json

{"email":"admin@hospital.test","password":"password","name":"integrasi-backoffice"}
```

Gunakan `Authorization: Bearer <token>` untuk `/api/v1/*`. Token plaintext hanya dikembalikan sekali; database menyimpan SHA-256 hash. Session auth tetap diterima untuk internal browser, sedangkan client eksternal wajib bearer token. Endpoint memakai JSON validation dan pagination.

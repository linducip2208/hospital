# BACKUP-RESTORE (verifikasi kode, 2026-10-21)

Implementasi: `app/Console/Commands/BackupDatabase.php` (`db:backup --keep=14`), cron harian `0 2 * * *` via `deploy.sh` (`step_cron_and_queue`), file di `storage/app/backups` (disk local), mysqldump + gzip (lihat PRODUCTION-DEPLOY.md §7).

Yang terverifikasi dari kode: perintah ada, retensi 14 file (deploy.sh menyebut keep=30 di cron — minor inkonsistensi dokumentasi, default command 14), exit code failure jelas, tanpa kredensial di log.

Yang BELUM: uji restore end-to-end di environment isolasi (NOT TESTED). Jangan klaim dapat dipulihkan sebelum diuji:
rencana uji — restore dump ke DB kosong staging, jalankan `migrate --force`, verifikasi login + buka 1 rekam medis + 1 invoice.

Akses backup: batasi `root:www-data 0600` pada direktori backup; backup berisi data pasien → jangan diunduh ke laptop tanpa enkripsi.

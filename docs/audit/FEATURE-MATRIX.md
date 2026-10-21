# FEATURE MATRIX — SIMRS Hospital

Legenda status: VERIFIED (tes lulus + bukti) · PARTIAL (terverifikasi sebagian) · NOT TESTED (butuh env/kredensial eksternal).

## Modul inti (web + API)

| Modul | Menu/UI | Route web | API | Permission | Test | Status | Bukti |
|---|---|---|---|---|---|---|---|
| Dashboard | ✅ sidebar | `dashboard` | — | auth | DashboardTest | VERIFIED | suite hijau |
| Pasien | ✅ | `patients.*` | `GET/POST/PUT/PATCH/DELETE /v1/patients` + hapus khusus admin/dev | patients.view/create/update | PatientControllerTest, ApiTokenTest, FlutterApiTest | VERIFIED | 200/403/404 |
| Appointment | ✅ | `appointments.*` | `/v1/appointments` + calendar; hapus butuh appointments.manage | appointments.manage (hapus) | AppointmentControllerTest, ApiTokenTest | VERIFIED | suite hijau |
| Rekam medis | ✅ | `medical-records.*` | `/v1/medical-records`; hapus butuh medical_records.sign | mixed | MedicalRecordControllerTest, ApiTokenTest | VERIFIED | suite hijau |
| Dokter/Treatment | ✅ | `doctors.*`, `treatments.*` | `/v1/doctors`, `/v1/treatments`; hapus admin/dev | —/auth | Doctor/TreatmentApiTest | VERIFIED | suite hijau |
| Pembayaran/Billing | ✅ | `payments.*`, `billing/*` | `/v1/payments` + refund resmi; edit/hapus final dilarang | billing.view/manage, payments.receive/refund | PaymentControllerTest, FinancialIntegrityTest (4) | VERIFIED | 422/403/201 |
| 54 modul generik | ✅ sidebar | resource web | `/v1/{modul}` CRUD + peran sensitif | baca/tulis staf; hapus admin/dev (kec. queues, ambulance-calls) | ApiSmokeTest (62 asersi), ApiSecurityTest | VERIFIED | 200/403/404 |
| Portal pasien | ✅ `/portal` | portal.* (ownership scoping) | `/v1/me/*` milik sendiri | guard patient + ownership | PatientPortalTest, FlutterApiTest, PublicApiTest | VERIFIED | cross-patient 403 |
| API publik | — | — | `v1/public/*` (throttle 30/5) | tanpa auth, disaring | PublicApiTest | VERIFIED | 200/201/401-staf-tetap |
| Auth staf/web | ✅ login | login/logout/register | token+refresh+expiry+revoke | throttle login 5/mnt | AuthenticationTest, ApiTokenTest | VERIFIED | 401/403/204 |
| SatuSehat | ✅ settings | settings.satusehat.* | webhook HMAC | settings.manage | kod review + diagnose cmd | PARTIAL | butuh kredensial live |
| Lisensi/pairing | ✅ `/__pair` | pair.* (throttle) | JSON 403 bila belum pairing | — | kod review + ProductionCheck | PARTIAL | tanpa server lisensi live |
| Backup/restore | via scheduler | — | — | admin | kod review | NOT TESTED | butuh env isolasi |

## Modul operasional lain (keperawatan, IGD, farmasi, lab, radiologi, HR, logistik, BPJS)

CRUD web + API generik terverifikasi via smoke test (index 200, show-404 404) untuk seluruh 54 slug di `config/api_resources.php`.
Aturan klinis tidak diubah; tidak ada klaim keputusan medis otomatis. Detail per-modul: smoke test `ApiSmokeTest::test_semua_route_api_terdaftar_dan_bisa_diakses`.

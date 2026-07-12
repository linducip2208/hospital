# Hospital SIRS — Database Architecture

> Dokumentasi arsitektur lengkap database aplikasi Hospital SIRS (SIMRS Whitelabel) berbasis Laravel 12.
> Last updated: 2026-05-01

---

## Daftar Isi

1. [Ringkasan](#ringkasan)
2. [Konvensi Skema](#konvensi-skema)
3. [Modul & Tabel](#modul--tabel)
4. [Diagram Relasi (ER)](#diagram-relasi-er)
5. [Detail Tabel per Modul](#detail-tabel-per-modul)
6. [Indexing Strategy](#indexing-strategy)
7. [Soft Delete & Audit](#soft-delete--audit)
8. [Migration History](#migration-history)

---

## Ringkasan

Database aplikasi mengelola **78 tabel** yang terorganisir ke dalam **15 modul** fungsional:

| Modul | Jumlah Tabel | Deskripsi |
|---|---|---|
| Identitas & Auth | 5 | users, roles, sessions, password resets, permissions |
| Pasien & Dokter | 4 | patients, doctors, polyclinics, doctor_polyclinic |
| Pelayanan Klinis | 9 | appointments, queues, treatments, medical_records, lab_tests, radiologies, surgeries, emergencies, referrals |
| Rawat Inap & ICU | 5 | rooms, hospital_beds, icu_monitorings, vital_signs_records, medication_administrations |
| Keperawatan | 5 | nursing_cares, nurse_assignments, shift_handovers, vital_signs_records (shared), medication_administrations (shared) |
| Kebidanan | 5 | maternities, anc_records, partographs, postnatal_records, baby_immunizations |
| Bank Darah & Ambulans | 3 | blood_donations, ambulances, ambulance_calls |
| Farmasi | 5 | drugs, prescriptions, prescription_items, drug_supply_orders, drug_destructions |
| Surat & Cetak | 9 | medical_certificates, informed_consents, prescriptions, drug_supply_orders, drug_destructions, cost_estimates, insurance_claims, discharge_summaries, patient_screenings |
| Mutu & Keselamatan | 5 | patient_safety_incidents, code_blue_activations, infection_surveillances, clinical_pathways, patient_feedbacks |
| HR & Payroll | 5 | employees, attendances, salaries, leaves, departments |
| Logistik & Aset | 5 | assets, equipment_maintenances, purchase_orders, purchase_order_items, staff_schedules |
| Keuangan & Akuntansi | 5 | payments, chart_of_accounts, journal_entries, journal_entry_lines, insurance_claims |
| Telemedicine & Diet | 3 | telemedicine_sessions, diet_orders, odontograms |
| CMS & Sistem | 4 | settings, page_contents, cache, jobs |

---

## Konvensi Skema

### Penamaan
- **Tabel**: `snake_case`, jamak (`patients`, `medical_records`)
- **Kolom**: `snake_case`, deskriptif (`birth_date`, `is_active`, `bpjs_number`)
- **Foreign key**: `{singular_table}_id` (`patient_id`, `doctor_id`)
- **Pivot table**: alfabetis (`doctor_polyclinic`)
- **Boolean**: prefix `is_` atau `has_` (`is_active`, `has_bpjs`)
- **Datetime**: suffix `_at` (`created_at`, `signed_at`, `discharge_date`)
- **Date saja**: tanpa suffix khusus (`birth_date`, `exam_date`)

### Tipe Data Standar
- ID: `bigIncrements` / `unsignedBigInteger`
- Nominal uang: `decimal(14, 2)` (max 14 digit, 2 desimal)
- Persentase / suhu: `decimal(4, 1)` atau `decimal(5, 2)`
- Status / enum kecil: `enum(...)` dengan index
- JSON dinamis: `json` (kemudian dicast `'json'`)
- Geo: `decimal(10, 7)` untuk lat/lng (jika diperlukan)

### Default & Constraint
- **All tables** punya `created_at` & `updated_at` (`timestamps()`)
- **Tabel utama** punya `deleted_at` (`softDeletes()`) — tidak hilangkan data, hanya tandai
- **Foreign key** wajib pakai constraint: `cascadeOnUpdate`, `restrictOnDelete` untuk parent yang penting (Patient, Doctor), `nullOnDelete` untuk relasi opsional
- **Unique** pada nomor dokumen (`cert_no`, `claim_no`, `rx_no`, `invoice_number`, dll.)
- **Index** pada kolom yang sering difilter (`status`, `type`, `patient_id`, `doctor_id`)

### Enum Standar
- `status` document: `draft → issued → cancelled` (atau `dispensed`/`completed`)
- `gender`: `male | female`
- `risk_level`: `low | moderate | high`
- `severity`: `none | minor | moderate | major | catastrophic`

---

## Modul & Tabel

### Diagram Relasi (ER)

```
┌─────────────┐         ┌───────────────┐
│   users     │1───*    │  employees    │
│  (auth)     │         │  (HR profile) │
└─────────────┘         └───────────────┘
      │1                       │
      │                        │
      *                        *
┌─────────────┐         ┌───────────────┐
│  patients   │1───*    │  doctors      │
└─────┬───────┘         └──────┬────────┘
      │                        │
      │ 1───*                  │ 1───*
      ▼                        ▼
┌─────────────────────────────────────┐
│  appointments  ◄─── medical_records │
│       │                  │          │
│       │ 1───*            │          │
│       ▼                  ▼          │
│  payments         prescriptions ────┐
│       │              │              │
│       │              ▼              │
│       └────► insurance_claims    prescription_items
└─────────────────────────────────────┘

┌─────────────┐    ┌──────────────────┐
│   rooms     │1──*│ hospital_beds    │1──* icu_monitorings
└─────────────┘    └──────────────────┘
                          │
                          *
                   current_patient_id → patients

┌──────────────────┐
│ medical_records  │1── medical_certificates
│                  │1── discharge_summaries
└──────────────────┘

┌──────────────────────┐
│ patient_safety_incidents │ ◄── reported by users
│ code_blue_activations    │
│ infection_surveillances  │
│ patient_feedbacks        │
└──────────────────────┘
```

---

## Detail Tabel per Modul

### 1. Identitas & Authentication

#### `users`
Auth user untuk staf RS (admin, dokter, perawat, kasir, dll.).
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigIncrements | PK |
| name | string | |
| email | string | unique |
| password | string | hashed |
| role | enum | `developer/admin/dokter/perawat/bidan/apoteker/lab/radiolog/kasir/admisi/sopir/cleaner` |
| department_id | FK departments | nullable |
| email_verified_at, remember_token | | |
| timestamps | | |

#### `password_reset_tokens`, `sessions`, `cache`, `jobs`
Standar Laravel.

---

### 2. Pasien & Dokter

#### `patients`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigIncrements | PK, dipakai sebagai No.RM |
| user_id | FK users | nullable (kalau pasien punya akun) |
| name, email, phone | string | |
| nik | string(16) | unique, NIK KTP |
| nik_verified | boolean | hasil cek Dukcapil |
| bpjs_number | string | nullable |
| birth_date | date | |
| gender | enum male/female | |
| address, blood_type, allergies, medical_history, emergency_contact_* | | |
| is_active | boolean | |
| timestamps + softDeletes | | |

**Index**: `nik`, `bpjs_number`, `(name, phone, email)`

#### `doctors`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id, user_id (FK), name, email, phone | | |
| specialization | string | |
| str_number | string | unique, Nomor STR |
| consultation_fee | decimal(14,2) | |
| status | enum active/inactive | |
| timestamps + softDeletes | | |

#### `polyclinics`
Daftar poliklinik (Umum, Anak, Bedah, dll.).

#### `doctor_polyclinic` (pivot)
Relasi M-N dokter ↔ poli.

---

### 3. Pelayanan Klinis

#### `appointments`
Janji temu pasien-dokter.
- `patient_id`, `doctor_id`, `polyclinic_id`, `date`, `time`
- `status`: `scheduled/checked_in/in_progress/completed/cancelled/no_show`

#### `queues`
Antrian harian (loket).
- `patient_id`, `doctor_id`, `polyclinic_id`, `queue_number`, `date`
- `status`: `waiting/called/serving/completed/skipped`

#### `treatments`
Daftar tindakan medis & tarif.
- `name`, `code`, `category`, `price`, `description`

#### `medical_records`
Catatan rekam medis per kunjungan.
- `patient_id`, `doctor_id`, `appointment_id`
- `diagnosis`, `action`, `medicine`, `vital_signs (json)`, `lab_results`, `notes`
- softDeletes (tidak boleh hilang permanen)

#### `lab_tests`
- `patient_id`, `doctor_id`, `test_name`, `test_type`, `sample_type`
- `status`, `results`, `result_date`, `notes`

#### `radiologies`
- `patient_id`, `doctor_id`, `examination_name`, `body_part`
- `status`, `findings`, `notes`

#### `surgeries`
- `patient_id`, `doctor_id`, `name`, `description`
- `scheduled_date`, `status`, `notes`

#### `emergencies`
Pasien IGD.
- `patient_id`, `doctor_id`, `triage` (red/yellow/green/black/observation)
- `arrival_mode`, `complaint`, `diagnosis`, `action_taken`
- `status`, `discharge_date`, `notes`

#### `referrals`
- `patient_id`, `from_polyclinic_id`, `to_polyclinic_id`, `doctor_id`
- `reason`, `diagnosis`, `status`, `notes`

---

### 4. Rawat Inap & ICU

#### `rooms`
Daftar kamar rawat inap.
- `name`, `room_number`, `room_type` (vip/kelas_1/kelas_2/kelas_3/icu/icu_anak/nicu/picu/isolasi/hcu)
- `capacity`, `price_per_day`, `status`

#### `hospital_beds` ⭐ NEW
Per-bed tracking dalam kamar.
| Kolom | Tipe | |
|---|---|---|
| id | bigIncrements | |
| room_id | FK rooms | restrictOnDelete |
| bed_code | string unique | |
| label | string nullable | "Window side", "Bed 1" |
| status | enum | `available/occupied/reserved/cleaning/maintenance/blocked` |
| current_patient_id | FK patients nullable | siapa yang menempati |
| occupied_since | datetime nullable | |
| notes | text | |
| timestamps + softDeletes | | |

#### `icu_monitorings` ⭐ NEW
Monitoring high-frequency vital signs ICU.
- `patient_id`, `hospital_bed_id`, `recorded_at`
- `temperature`, `hr`, `rr`, `sbp`, `dbp`, `map`, `spo2`, `gcs`, `cvp`
- `ventilator (json)`, `drips (json)`, `notes`
- Index: `(patient_id, recorded_at)` untuk chart vital trend

#### `vital_signs_records`
TTV regular (rawat inap, rawat jalan).
- `patient_id`, `nurse_id`, `recorded_at`
- `temperature`, `bp_systolic`, `bp_diastolic`, `heart_rate`, `respiratory_rate`, `oxygen_saturation`

#### `medication_administrations`
Catatan pemberian obat (MAR sheet).
- `patient_id`, `nurse_id`, `drug_id`, `drug_name`
- `dosage`, `route`, `administered_at`, `notes`

---

### 5. Keperawatan

#### `nursing_cares`
SOAP keperawatan (S-O-A-P + I-E).
- `patient_id`, `nurse_id`, `care_date`
- `subjective`, `objective`, `assessment`, `plan`, `implementation`, `evaluation`

#### `nurse_assignments`
Penugasan perawat ke pasien.
- `patient_id`, `nurse_id`, `assigned_at`, `unassigned_at`, `role`, `notes`

#### `shift_handovers`
Serah terima antar shift.
- `from_nurse_id`, `to_nurse_id`, `handover_at`, `unit`, `summary`

---

### 6. Kebidanan

#### `maternities`
Catatan persalinan.
- `patient_id`, `doctor_id`/`midwife_id`
- `delivery_date`, `delivery_method`, `outcome`

#### `anc_records`
Antenatal care.
- `patient_id`, `gestational_age`, `tfu`, `djj`, dll.

#### `partographs`
Partograf persalinan.
- `maternity_id`, `recorded_at`
- `cervical_dilation`, `fetal_heart_rate`, `contractions_per_10min`, `amniotic_fluid`, `moulding`, `oxytocin`

#### `postnatal_records`
Pemeriksaan ibu nifas.

#### `baby_immunizations`
Tracking imunisasi bayi.

---

### 7. Bank Darah & Ambulans

#### `blood_donations`
- `patient_id` (donor), `blood_type`, `volume_ml`, `donation_date`, `notes`

#### `ambulances`
Armada ambulans.
- `name`, `plate_number`, `type`, `status`

#### `ambulance_calls`
Surat tugas ambulans.
- `ambulance_id`, `patient_name`, `pickup_location`, `destination`
- `call_date`, `status`, `notes`

---

### 8. Farmasi

#### `drugs`
Master obat.
- `name`, `category`, `unit`, `stock`, `price`, `description`, `is_active`

#### `prescriptions` ⭐ NEW
Header resep.
- `rx_no` unique, `patient_id`, `doctor_id`, `medical_record_id`
- `prescribed_at`, `is_iter`, `iter_count`
- `status`: `draft/issued/dispensed/cancelled`

#### `prescription_items` ⭐ NEW
Detail item per resep.
- `prescription_id`, `drug_id`, `drug_name`, `dose`, `frequency`, `route`, `duration`
- `quantity`, `unit`, `instructions`, `is_compounded`, `is_high_alert`

#### `drug_supply_orders` ⭐ NEW
Surat Pesanan obat (regular/narcotic/psychotropic/precursor).
- `order_no` unique, `order_type`, `order_date`
- `supplier_name`, `supplier_address`, `supplier_license_no`
- `responsible_pharmacist`, `pharmacist_sipa_no`, `status`

#### `drug_supply_order_items` ⭐ NEW
Item-item dalam SP.

#### `drug_destructions` ⭐ NEW
Berita Acara Pemusnahan.
- `destruction_no`, `destruction_date`, `location`, `method`
- `responsible_pharmacist`, `witness_name_1/2`, `witness_role_1/2`

#### `drug_destruction_items` ⭐ NEW
Item obat dimusnahkan + batch + ED.

---

### 9. Surat & Cetak Dokumen ⭐ NEW

#### `medical_certificates`
Surat keterangan medis (10 tipe).
- `cert_no` unique
- `type`: `sick_leave/healthy/drug_free/pregnancy/not_pregnancy/birth/death/visum/color_blind_free/medical_check_up`
- `patient_id`, `doctor_id`, `medical_record_id`
- `issue_date`, `rest_from`, `rest_until`, `rest_days`
- `diagnosis`, `purpose`, `exam_data (json)`, `notes`
- `status`: `draft/issued/cancelled`

#### `informed_consents`
Surat persetujuan/penolakan/APS.
- `consent_no` unique, `kind` (consent/refusal/aps)
- `patient_id`, `doctor_id`
- `procedure_name`, `procedure_description`, `risks`, `alternatives`
- `signed_by_name`, `signed_by_relation`, `witness_name`, `signed_at`

#### `cost_estimates` + `cost_estimate_items`
Estimasi biaya pra-tindakan.
- `estimate_no`, `patient_id`, `doctor_id`
- `procedure_name`, `total_amount`, `status`
- Items: `description`, `quantity`, `unit`, `unit_price`, `subtotal`

#### `insurance_claims`
Klaim asuransi/BPJS/Inhealth.
- `claim_no`, `patient_id`, `payment_id`
- `insurance_provider`, `policy_number`
- `claim_type`: `outpatient/inpatient/emergency/maternity`
- `service_date`, `claim_date`, `diagnosis_code` (ICD-10)
- `claimed_amount`, `approved_amount`
- `status`: `draft/submitted/approved/rejected/paid`

#### `discharge_summaries`
Resume Medis Pulang.
- `summary_no`, `patient_id`, `doctor_id`, `medical_record_id`
- `admission_date`, `discharge_date`
- `admission_diagnosis`, `discharge_diagnosis`
- `chief_complaint`, `history`, `physical_exam`, `investigations`
- `treatment`, `progress`, `discharge_medication`, `follow_up`
- `discharge_condition`: `recovered/improved/unchanged/worsened/died`

#### `patient_screenings`
Skrining (jatuh, nyeri, gizi).
- `screening_no`, `type`: `fall_risk/pain/nutrition/pediatric_fall`
- `patient_id`, `user_id`, `screened_at`
- `answers (json)`, `score`, `risk_level`, `intervention`

---

### 10. Mutu & Keselamatan Pasien ⭐ NEW

#### `patient_safety_incidents`
Insiden Keselamatan Pasien (KNC/KTC/KTD/KPC/Sentinel).
- `incident_no`, `incident_type`, `severity`
- `patient_id`, `reporter_id` (user)
- `occurred_at`, `reported_at`, `location`
- `description`, `immediate_action`, `root_cause`, `corrective_action`
- `status`: `reported/investigating/closed`

#### `code_blue_activations`
Log aktivasi Code Blue.
- `code_no`, `patient_id`, `location`
- `activation_time`, `team_arrival_time`, `return_circulation_time`, `end_time`
- `outcome`: `rosc/died/transferred/ongoing`
- `team_leader`, `initial_rhythm`, `interventions`, `medications_given`
- **Computed**: `response_time` (detik) = team_arrival - activation

#### `infection_surveillances`
HAIs surveillance (VAP/CLABSI/CAUTI/SSI/Phlebitis/Decubitus).
- `case_no`, `patient_id`, `infection_type`
- `detection_date`, `onset_date`, `site`, `organism`
- `symptoms`, `antibiotic_therapy`, `intervention`
- `outcome`: `resolved/ongoing/died`

#### `clinical_pathways`
Template pathway perawatan per diagnosis.
- `code` unique, `name`, `diagnosis_code`, `diagnosis`
- `expected_los_days`, `phases (json)` per hari
- `inclusion_criteria`, `exclusion_criteria`, `is_active`

#### `patient_feedbacks`
Survey kepuasan pasien.
- `patient_id`, `visit_date`, `service_type`
- `rating_overall`, `rating_doctor`, `rating_nurse`, `rating_facility`, `rating_cleanliness`, `rating_speed` (1-5)
- `would_recommend (boolean)`, `positive`, `negative`, `suggestion`
- `is_anonymous`, `respondent_name`, `respondent_contact`
- `status`: `new/reviewed/responded/closed`

---

### 11. HR & Payroll

#### `employees`
Data karyawan.
- `user_id`, `nip`, `position`, `department_id`, `hire_date`, `status`

#### `attendances`
Absensi.
- `employee_id`, `clock_in`, `clock_out`, `hours_worked`, `status`

#### `salaries`
Penggajian bulanan.
- `employee_id`, `period`, `basic_salary`, `allowances`, `deductions`, `net_salary`
- `status`: `draft/approved/paid`

#### `leaves`
Pengajuan cuti/izin.
- `employee_id`, `start_date`, `end_date`, `type`, `reason`, `status`

#### `departments`
Master departemen.

#### `staff_schedules`
Jadwal shift.
- `user_id`, `shift_date`, `shift_type`, `unit`

---

### 12. Logistik & Aset

#### `assets`
Inventaris alat.
- `name`, `code`, `category`, `serial_number`, `location`, `purchase_date`, `status`

#### `equipment_maintenances` ⭐ NEW
Jadwal & log maintenance alat medis.
- `asset_id`, `scheduled_date`, `performed_date`
- `maintenance_type`: `preventive/corrective/calibration/inspection`
- `performer`, `description`, `findings`, `action`, `cost`
- `result`: `ok/needs_repair/replaced/failed`
- `next_due_date`, `status`

#### `purchase_orders` + `purchase_order_items`
PO ke supplier.

---

### 13. Keuangan & Akuntansi

#### `payments`
Transaksi pembayaran pasien.
- `patient_id`, `appointment_id`, `invoice_number` unique
- `subtotal`, `discount`, `tax`, `amount`, `paid_amount`, `change_amount`
- `payment_method`, `status`, `notes`

#### `chart_of_accounts`
Bagan akun (GL).

#### `journal_entries` + `journal_entry_lines`
Entri jurnal akuntansi (double-entry).

---

### 14. Telemedicine, Diet & Odontogram ⭐ NEW

#### `telemedicine_sessions`
Sesi konsultasi online.
- `session_no`, `patient_id`, `doctor_id`
- `scheduled_at`, `started_at`, `ended_at`
- `platform` (Zoom/Meet/Teams), `meeting_url`, `meeting_id`
- `status`: `scheduled/ongoing/completed/no_show/cancelled`
- `chief_complaint`, `assessment`, `plan`, `fee`

#### `diet_orders`
Order diet pasien rawat inap.
- `order_no`, `patient_id`, `doctor_id`
- `order_date`, `start_date`, `end_date`
- `diet_type`: `regular/soft/liquid/puree/tube_feed/parenteral/diabetic/low_salt/low_protein/high_protein/low_fat/gluten_free/custom`
- `texture`, `calories`, `restrictions (json)`, `special_instructions`
- `status`: `active/paused/discontinued/completed`

#### `odontograms`
Pemeriksaan gigi.
- `patient_id`, `doctor_id`, `exam_date`
- `teeth_state (json)` — map FDI tooth number → state code (CAR/AMF/COF/RCT/CRN/EXT/MIS/IMP)
- `general_findings`, `treatment_plan`, `notes`

---

### 15. CMS & Sistem

#### `settings`
Key-value config bergrup.
- `key` unique, `value (text)`, `type`, `group`, `label`
- Group: `branding`, `bpjs`, `satu_sehat`

#### `page_contents`
CMS landing page.
- `section_key` unique, `title`, `content`, `metadata (json)`, `is_visible`, `sort_order`

---

## Indexing Strategy

| Pattern | Diterapkan pada |
|---|---|
| Single-column index pada FK | semua kolom `*_id` (otomatis Laravel) |
| Composite index `(patient_id, recorded_at)` | `icu_monitorings`, `vital_signs_records` |
| Index pada `status` | tabel dengan workflow (orders, claims, incidents) |
| Index pada `type` enum | `medical_certificates.type`, `patient_screenings.type`, `infection_surveillances.infection_type` |
| Unique pada nomor dokumen | `cert_no`, `consent_no`, `rx_no`, `claim_no`, `order_no`, `incident_no`, `code_no`, `case_no`, `summary_no`, `screening_no`, `invoice_number` |
| Unique pada `nik` (16 digit) | `patients` |
| Unique pada `str_number` | `doctors` |

---

## Soft Delete & Audit

### Soft Delete
Diterapkan pada **semua tabel data utama** untuk audit trail medis (peraturan menyimpan rekam medis 5+ tahun):
- `patients`, `doctors`, `medical_records`, `appointments`
- `lab_tests`, `radiologies`, `surgeries`, `emergencies`, `referrals`
- `medical_certificates`, `informed_consents`, `prescriptions`, `discharge_summaries`
- `patient_safety_incidents`, `infection_surveillances`, `code_blue_activations`
- `payments`, `insurance_claims`
- `hospital_beds`, `diet_orders`, `odontograms`, `telemedicine_sessions`
- semua HR (`employees`, `salaries`, `leaves`, `attendances`)

**Tidak** soft delete (data referensial / append-only):
- `prescription_items`, `cost_estimate_items`, `drug_supply_order_items`, `drug_destruction_items` (cascade on parent)
- `icu_monitorings` (high-volume, append-only)
- `journal_entry_lines` (akuntansi, immutable per entry)
- `settings`, `cache`, `jobs`, `sessions`

### Timestamps
- Semua tabel utama punya `created_at`, `updated_at`
- Tabel transaksi punya `*_at` field untuk milestone (`signed_at`, `discharge_date`, `activation_time`, `team_arrival_time`)

### Foreign Key Policy
| Parent | Child | Policy | Alasan |
|---|---|---|---|
| `patients` | `medical_records`, `appointments`, `payments` | `restrictOnDelete` | Pasien tidak boleh dihapus jika punya histori |
| `doctors` | `appointments`, `medical_records` | `nullOnDelete` | Saat dokter resign, histori tetap |
| `prescriptions` | `prescription_items` | `cascadeOnDelete` | Items adalah child kuat, ikut hapus parent |
| `users` | `patient_safety_incidents.reporter_id` | `nullOnDelete` | Reporter bisa keluar, laporan tetap |

---

## Migration History

```
0001_01_01_000000  create_users_table
0001_01_01_000001  create_cache_table
0001_01_01_000002  create_jobs_table
2026_04_29_202240  create_patients_table
2026_04_29_202241  create_doctors_table
2026_04_29_202242  create_treatments_table
2026_04_29_202243  create_appointments_table
2026_04_29_202244  create_medical_records_table
2026_04_29_202245  create_payments_table
2026_04_30_071057  fix_payment_status_and_medical_record_softdeletes
2026_04_30_082508  add_role_to_users_table
2026_04_30_150000  fix_column_names_enums_and_indexes
2026_04_30_160000  create_drugs_table
2026_04_30_160001  create_rooms_table
2026_04_30_161000  expand_user_roles
2026_04_30_170000  create_polyclinics_table
2026_04_30_170001  create_doctor_polyclinic_table
2026_04_30_170002  create_queues_table
2026_04_30_170003  create_settings_table
2026_04_30_180000  add_bpjs_to_patients
2026_04_30_180100  create_lab_tests_table
2026_04_30_180200  create_radiologies_table
2026_04_30_180300  create_maternities_table
2026_04_30_180400  create_emergencies_table
2026_04_30_180500  create_referrals_table
2026_04_30_190000  create_surgeries_table
2026_04_30_190100  create_blood_donations_table
2026_04_30_190200  create_staff_schedules_table
2026_04_30_190300  create_ambulances_table
2026_04_30_190400  create_ambulance_calls_table
2026_04_30_190500  create_page_contents_table
2026_04_30_200000  add_bidan_role
2026_04_30_200100  create_nurse_assignments_table
2026_04_30_210000  add_patient_fk_to_blood_donations
2026_04_30_220000  create_vital_signs_records_table
2026_04_30_220100  create_medication_administrations_table
2026_04_30_220200  create_nursing_cares_table
2026_04_30_220300  create_shift_handovers_table
2026_04_30_220400  create_anc_records_table
2026_04_30_220500  create_partographs_table
2026_04_30_220600  create_postnatal_records_table
2026_04_30_220700  create_baby_immunizations_table
2026_04_30_230000  create_employees_table
2026_04_30_230100  create_attendances_table
2026_04_30_230200  create_salaries_table
2026_04_30_230500  create_departments_table
2026_04_30_230600  assign_user_department
2026_04_30_230700  create_assets_table
2026_04_30_230800  create_purchase_orders_table
2026_04_30_230900  create_purchase_order_items_table
2026_04_30_231000  create_chart_of_accounts_table
2026_04_30_231100  create_journal_entries_table
2026_04_30_231200  create_journal_entry_lines_table
2026_04_30_231300  create_leaves_table
2026_04_30_231400  expand_user_roles_full
2026_05_01_000000  fix_medical_fk_policies_and_soft_deletes
2026_05_01_050047  extend_room_type_enum
2026_05_01_140000  extend_page_contents_and_add_developer_role
2026_05_01_220000  create_printable_documents_tables ⭐
2026_05_01_230000  create_advanced_hospital_features_tables ⭐
```

---

## Quality Indicators (KPI Hospital)

Aplikasi siap menghitung KPI standar hospital dari data yang ada:

| Metric | Formula | Source |
|---|---|---|
| **BOR** (Bed Occupancy Rate) | `(jumlah hari rawat / (jumlah TT × hari)) × 100%` | `hospital_beds` × `discharge_summaries` |
| **ALOS** (Average Length of Stay) | `total hari rawat / total pasien keluar` | `discharge_summaries` |
| **TOI** (Turn Over Interval) | `(TT × hari − hari rawat) / pasien keluar` | `hospital_beds` × `discharge_summaries` |
| **BTO** (Bed Turn Over) | `pasien keluar / TT` | sda |
| **NDR** (Net Death Rate) | `kematian ≥48jam / pasien keluar × 1000‰` | `discharge_summaries.discharge_condition='died'` + diff dates |
| **GDR** (Gross Death Rate) | `total kematian / pasien keluar × 1000‰` | sda |
| **HAIs Rate** | `kasus HAI / 1000 hari kateter/CVC` | `infection_surveillances` |
| **Code Blue Response Time** | avg `team_arrival - activation` | `code_blue_activations` |
| **Patient Satisfaction Score** | avg `rating_overall` | `patient_feedbacks` |

---

## Catatan Pengembang

### Cara Menambah Modul Baru
1. Buat migration di `database/migrations/`
2. Buat Model di `app/Models/` dengan relations
3. Buat Controller di `app/Http/Controllers/` (resource-style)
4. Buat folder views di `resources/views/{module-slug}/`
5. Tambah `Route::resource(...)` di `routes/web.php` dalam group `auth`
6. Tambah link menu di `resources/views/layouts/admin.blade.php`

### Konvensi Status Document
Semua dokumen yang punya lifecycle pakai pattern:
- `draft` → `issued` → `cancelled` (untuk surat/sertifikat)
- `draft` → `submitted` → `approved/rejected` → `paid` (untuk klaim/PO)
- `scheduled` → `in_progress` → `done/cancelled` (untuk task/maintenance)

### Soft Delete Recovery
```php
$model = MedicalCertificate::withTrashed()->find($id);
$model->restore();
```

### JSON Casts
Field JSON dipakai untuk data yang bersifat **opsional & dinamis** (tidak perlu query):
- `medical_certificates.exam_data` — varies per certificate type
- `patient_screenings.answers` — kuesioner per tipe skrining
- `clinical_pathways.phases` — struktur pathway hari per hari
- `icu_monitorings.ventilator`, `drips` — pengaturan ventilator/drip dinamis
- `medical_records.vital_signs` — TTV inline
- `diet_orders.restrictions` — daftar makanan pantang

JSON tidak boleh dipakai untuk: ID (pakai FK), filter berkala (pakai kolom), agregasi (pakai kolom).

---

**Dokumen ini auto-update saat ada migration baru. Jangan edit isi tabel langsung — selalu via migration agar production deploy aman.**

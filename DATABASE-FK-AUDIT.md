# Database FK Audit — Alur Bisnis vs Schema

Audit relasi PK/FK terhadap **patient journey** (alur pasien) dan **business flow** (alur bisnis operasional rumah sakit).

**Total tabel:** 75 (60 migration files)
**Tanggal audit:** 2026-05-22

---

## 🩺 Patient Journey & FK Chain

### Tahap 1 — Pendaftaran Pasien
```
patients (PK: id)
  └─ user_id → users (nullable, untuk pasien self-registered)
```

### Tahap 2 — Booking & Antrian
```
appointments (PK: id)
  ├─ patient_id     → patients
  ├─ doctor_id      → doctors
  └─ treatment_id   → treatments (nullable)

queues (PK: id)
  ├─ polyclinic_id  → polyclinics
  ├─ patient_id     → patients
  └─ doctor_id      → doctors (nullable)

patient_screenings (PK: id)
  ├─ patient_id        → patients
  └─ user_id           → users (nullable, petugas skrining)
```

### Tahap 3 — IGD & Gawat Darurat
```
emergencies (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors (nullable)

code_blue_activations (PK: id)
  └─ patient_id     → patients

ambulance_calls (PK: id)
  └─ ambulance_id   → ambulances
```

### Tahap 4 — Asuhan Keperawatan
```
nurse_assignments (PK: id)
  ├─ user_id        → users (nurse)
  └─ patient_id     → patients

vital_signs_records (PK: id)
  ├─ patient_id     → patients
  └─ nurse_id       → users

medication_administrations (PK: id)
  ├─ patient_id     → patients
  ├─ nurse_id       → users
  └─ drug_id        → drugs

nursing_cares (PK: id, SOAP records)
  ├─ patient_id     → patients
  └─ nurse_id       → users

shift_handovers (PK: id)
  ├─ from_nurse_id  → users
  └─ to_nurse_id    → users
```

### Tahap 5 — Penunjang Diagnostik
```
lab_tests (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors

radiologies (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors

blood_donations — stok darah (tidak per pasien)
```

### Tahap 6 — Rekam Medis (Pusat Klinis)
```
medical_records (PK: id) ★ HUB UTAMA
  ├─ patient_id      → patients
  ├─ doctor_id       → doctors
  └─ appointment_id  → appointments

prescriptions (PK: id)
  ├─ patient_id        → patients
  ├─ doctor_id         → doctors (nullable)
  └─ medical_record_id → medical_records (nullable)

prescription_items (PK: id)
  ├─ prescription_id → prescriptions (CASCADE on delete)
  └─ drug_id         → drugs

informed_consents (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors (nullable)

referrals (PK: id)
  ├─ patient_id          → patients
  ├─ from_polyclinic_id  → polyclinics
  ├─ to_polyclinic_id    → polyclinics
  └─ doctor_id           → doctors (nullable)

surgeries (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors

telemedicine_sessions (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors

odontograms (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors
```

### Tahap 7 — Kebidanan & Anak
```
maternities (PK: id) ★ HUB KEBIDANAN
  ├─ patient_id     → patients
  └─ doctor_id      → doctors

anc_records (PK: id, antenatal)
  ├─ patient_id     → patients
  └─ midwife_id     → users

partographs (PK: id, intrapartum)
  └─ maternity_id   → maternities

postnatal_records (PK: id)
  ├─ patient_id     → patients
  ├─ midwife_id     → users
  └─ maternity_id   → maternities

baby_immunizations (PK: id)
  ├─ patient_id     → patients (bayi)
  └─ maternity_id   → maternities (nullable, untuk imunisasi neonatal)
```

### Tahap 8 — Rawat Inap & ICU
```
rooms (PK: id, master kamar)

hospital_beds (PK: id) ★ status real-time
  ├─ room_id            → rooms
  └─ current_patient_id → patients (nullable, NULL = bed kosong)

icu_monitorings (PK: id)
  ├─ patient_id      → patients
  └─ hospital_bed_id → hospital_beds

diet_orders (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors
```

### Tahap 9 — Pulang & Dokumen
```
discharge_summaries (PK: id)
  ├─ patient_id        → patients
  ├─ doctor_id         → doctors (nullable)
  └─ medical_record_id → medical_records (nullable)

medical_certificates (PK: id)
  ├─ patient_id        → patients
  ├─ doctor_id         → doctors (nullable)
  └─ medical_record_id → medical_records (nullable)

cost_estimates (PK: id)
  ├─ patient_id     → patients
  └─ doctor_id      → doctors (nullable)

cost_estimate_items (PK: id)
  └─ cost_estimate_id → cost_estimates (CASCADE)

insurance_claims (PK: id)
  ├─ patient_id  → patients
  └─ payment_id  → payments (nullable)
```

### Tahap 10 — Kasir & Pembayaran
```
payments (PK: id)
  ├─ patient_id      → patients
  └─ appointment_id  → appointments (nullable)
```

### Tahap 11 — Mutu & Audit
```
patient_safety_incidents (PK: id, IKP)
  ├─ patient_id   → patients
  └─ reporter_id  → users

infection_surveillances (PK: id, HAIs)
  └─ patient_id   → patients

patient_feedbacks (PK: id)
  └─ patient_id   → patients

clinical_pathways (PK: id, master template)
  └─ (no FK — master data)

equipment_maintenances (PK: id)
  └─ asset_id     → assets
```

### Tahap 12 — Apotek & Farmasi
```
drugs (PK: id, master obat)

drug_supply_orders (PK: id, SP)
  └─ (NO FK — gap! tidak ada created_by/supplier)

drug_supply_order_items (PK: id)
  ├─ drug_supply_order_id → drug_supply_orders (CASCADE)
  └─ drug_id              → drugs

drug_destructions (PK: id, BAP)
  └─ (NO FK — gap! tidak ada created_by/witnessed_by)

drug_destruction_items (PK: id)
  ├─ drug_destruction_id → drug_destructions (CASCADE)
  └─ drug_id             → drugs
```

### Tahap 13 — SDM & Master
```
users (PK: id, root authentication)

doctors (PK: id)
  └─ user_id → users (nullable)

employees (PK: id)
  └─ user_id → users (nullable)

departments (PK: id)
polyclinics (PK: id)

doctor_polyclinic (PIVOT)
  ├─ doctor_id     → doctors
  └─ polyclinic_id → polyclinics

staff_schedules (PK: id)
  └─ user_id → users
```

### Tahap 14 — HR Operasional
```
attendances (PK: id)
  └─ user_id → users

salaries (PK: id)
  ├─ employee_id → employees
  └─ user_id     → users (kalau employee dilink ke akun)

leaves (PK: id)
  ├─ user_id     → users
  └─ approved_by → users (nullable)
```

### Tahap 15 — Akuntansi
```
chart_of_accounts (PK: id, hierarki)
  └─ parent_id → chart_of_accounts (self-reference, nullable)

journal_entries (PK: id)
  └─ posted_by → users (nullable, audit)

journal_entry_lines (PK: id) ★ double-entry validation
  ├─ journal_entry_id → journal_entries (CASCADE)
  └─ account_id       → chart_of_accounts
```

### Tahap 16 — Logistik
```
assets (PK: id)
  └─ department_id → departments

purchase_orders (PK: id)
  └─ department_id → departments

purchase_order_items (PK: id)
  └─ purchase_order_id → purchase_orders (CASCADE)
```

---

## ⚠️ Gap & Anomali yang Ditemukan

### 🔴 Critical — perlu fix segera

| # | Tabel | Gap | Dampak |
|---|---|---|---|
| 1 | `payments` | Missing `medical_record_id` | Tidak bisa trace billing → rekam medis spesifik (penting untuk asuransi & audit) |
| 2 | `drug_supply_orders` | Missing `created_by` (user_id) | Tidak auditable — siapa yang bikin SP |
| 3 | `drug_destructions` | Missing `created_by` & `witnessed_by` | BAP pemusnahan harus jelas siapa pelaku + saksi (legal CPOB) |
| 4 | `patient_screenings` | `medical_record_id` mungkin bermasalah | Skrining biasanya **sebelum** MR dibuat — FK harus nullable |

### 🟡 Medium — sebaiknya difix

| # | Tabel | Gap | Dampak |
|---|---|---|---|
| 5 | `medical_records` ↔ `treatments` | Tidak ada pivot M:N | 1 rekam medis hanya bisa 1 treatment (via appointment) — limit operasi |
| 6 | `prescriptions` | Missing `appointment_id` | Resep tidak terhubung ke kunjungan spesifik (cuma ke MR) |
| 7 | `vital_signs_records` | Missing `appointment_id` atau `nursing_care_id` | Vital sign tidak ter-link ke visit/asuhan |
| 8 | `nursing_cares` | Missing `appointment_id` | SOAP cuma tahu pasien & perawat, tidak tahu visit |
| 9 | `shift_handovers` | Missing pivot `shift_handover_patients` | Handover seharusnya bisa multiple pasien |
| 10 | `clinical_pathways` | Tidak ada link ke diagnosis/treatment template | Master CP tidak bisa di-apply ke rekam medis |

### 🟢 Low — nice to have

| # | Tabel | Gap | Catatan |
|---|---|---|---|
| 11 | `rooms.status` | Duplikasi state dengan `hospital_beds` | Status okupansi cukup di hospital_beds (sumber kebenaran tunggal) |
| 12 | `appointments` | Missing `polyclinic_id` | Saat ini polyclinic baru tahu via doctor — kurang langsung |
| 13 | `referrals` | Missing `medical_record_id` | Rujukan harusnya ter-link ke MR yang jadi sumber |
| 14 | `lab_tests` & `radiologies` | Missing `appointment_id` atau `medical_record_id` | Order lab/radiologi harusnya ter-link ke visit |
| 15 | `blood_donations` | Missing `donor_patient_id` | Donor darah bisa pasien sendiri, perlu trace |

---

## ✅ Konsistensi yang sudah baik

1. **CASCADE on delete** dipakai pada relasi item (prescription_items, journal_entry_lines, dll.) → cleanup otomatis
2. **nullable FK** untuk relasi opsional (doctor_id di consents, medical_record_id di certificates) → tidak block insert
3. **Soft delete** di tabel utama (`patients`, `doctors`, `medical_records`) → audit trail
4. **Double-entry validation** di `journal_entries` & `journal_entry_lines` → akuntansi auditable
5. **Self-reference** di `chart_of_accounts.parent_id` → hierarki akun fleksibel

---

## 📋 Rekomendasi Fix Migration (akan dibuat)

Akan dibuat 1 migration consolidated `2026_05_22_add_business_flow_fk_fixes.php` yang:

1. Add `payments.medical_record_id` (nullable FK)
2. Add `drug_supply_orders.created_by` (nullable FK ke users)
3. Add `drug_destructions.created_by` & `witnessed_by` (nullable FK ke users)
4. Make `patient_screenings.medical_record_id` nullable (atau drop kalau redundant)
5. Create pivot `medical_record_treatments` (M:N)
6. Add `prescriptions.appointment_id` (nullable FK)
7. Add `vital_signs_records.appointment_id` & `nursing_cares.appointment_id` (nullable FK)
8. Add `appointments.polyclinic_id` (nullable FK)
9. Add `referrals.medical_record_id` (nullable FK)
10. Add `lab_tests.appointment_id` & `radiologies.appointment_id` (nullable FK)

**Semua FK baru nullable** → backward compatible, data existing tidak break.

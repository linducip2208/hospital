# Arsitektur

Laravel 13 tetap menjadi modular monolith dengan MySQL relational, Blade/Bootstrap, database queue, dan service layer.

```mermaid
flowchart LR
 P[Patient] --> R[Registration/Appointment] --> E[Encounter]
 E --> C[Clinical record + Diagnosis]
 E --> O[Clinical Orders] --> L[Lab/Radiology]
 C --> RX[Prescription] --> D[Dispensing + FEFO]
 E --> CH[Charges]; D --> CH; CH --> B[Bill/Invoice]
 B --> Pay[Payment Allocation] --> J[General Ledger]
 E --> SS[Queue SatuSehat]
```

Encounter, charge, stock movement, payment allocation, journal source, dan audit event adalah sumber ketertelusuran. Modul lama dipertahankan; kolom FK nullable menghindari pemutusan data historis.

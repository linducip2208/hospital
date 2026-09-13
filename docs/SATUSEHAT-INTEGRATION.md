# SatuSehat

`SatuSehatClient` membaca konfigurasi dinamis. `SyncPatientToSatuSehat` dan `SyncEncounterToSatuSehat` mendelegasikan request ke `SyncSatuSehatResource` melalui Laravel Queue. Mapping disimpan di `satu_sehat_resources` dengan payload hash, attempts, status, resource ID, timestamp, dan error.

Resource FHIR yang disiapkan mencakup Patient, Encounter, Condition/Diagnosis, Observation, Medication/MedicationRequest, ServiceRequest, DiagnosticReport, Procedure, Practitioner, dan Organization. Mapping profil nasional tetap harus divalidasi saat onboarding faskes.

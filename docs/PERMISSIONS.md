# RBAC dan Data Access

Permission disimpan di `permissions` dan `role_permissions`, bukan hanya label sidebar. Middleware `permission:*` mengembalikan 403; admin/developer adalah break-glass role operasional.

Permission penting: `patients.view/create/update`, `medical_records.view/create/sign`, `encounters.manage`, `diagnoses.manage`, `clinical_orders.manage`, `prescriptions.dispense`, `lab.results.enter/verify`, `radiology.verify`, `billing.view/manage`, `payments.receive/refund`, `accounting.view/post`, `reports.export`, `audit_logs.view`, `users.manage`, `settings.manage`.

Policy pasien dan MedicalRecord memeriksa permission dan konteks dokter/unit. Portal wajib ownership check.

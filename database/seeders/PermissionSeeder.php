<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'patients.view' => 'Lihat pasien', 'patients.create' => 'Registrasi pasien', 'patients.update' => 'Ubah pasien',
            'appointments.manage' => 'Kelola appointment', 'encounters.manage' => 'Kelola encounter', 'medical_records.view' => 'Lihat rekam medis',
            'medical_records.create' => 'Buat rekam medis', 'medical_records.sign' => 'Finalize rekam medis', 'diagnoses.manage' => 'Kelola diagnosis',
            'clinical_orders.manage' => 'Kelola clinical order', 'prescriptions.create' => 'Buat resep', 'prescriptions.dispense' => 'Dispensing resep',
            'lab.results.enter' => 'Input hasil lab', 'lab.results.verify' => 'Verifikasi hasil lab', 'radiology.verify' => 'Verifikasi radiologi',
            'billing.view' => 'Lihat billing', 'billing.manage' => 'Kelola billing', 'payments.receive' => 'Terima pembayaran', 'payments.refund' => 'Refund pembayaran',
            'accounting.view' => 'Lihat akuntansi', 'accounting.post' => 'Posting jurnal', 'reports.export' => 'Export laporan', 'audit_logs.view' => 'Lihat audit trail',
            'users.manage' => 'Kelola pengguna', 'settings.manage' => 'Kelola pengaturan',
        ];
        foreach ($items as $name => $label) Permission::updateOrCreate(['name' => $name], ['label' => $label]);
        $all = Permission::pluck('id', 'name');
        $roles = [
            'director' => ['patients.view', 'medical_records.view', 'billing.view', 'accounting.view', 'reports.export', 'audit_logs.view'],
            'doctor' => ['patients.view', 'appointments.manage', 'encounters.manage', 'medical_records.view', 'medical_records.create', 'medical_records.sign', 'diagnoses.manage', 'clinical_orders.manage', 'prescriptions.create'],
            'nurse' => ['patients.view', 'appointments.manage', 'encounters.manage', 'medical_records.view', 'clinical_orders.manage'],
            'pharmacist' => ['patients.view', 'prescriptions.dispense', 'billing.view'],
            'lab_technician' => ['patients.view', 'clinical_orders.manage', 'lab.results.enter', 'lab.results.verify'],
            'cashier' => ['patients.view', 'billing.view', 'billing.manage', 'payments.receive'],
            'finance' => ['billing.view', 'accounting.view', 'accounting.post', 'reports.export'],
            'staff' => ['patients.view', 'patients.create', 'patients.update', 'appointments.manage'],
        ];
        foreach ($roles as $role => $permissions) foreach ($permissions as $name) if (isset($all[$name])) DB::table('role_permissions')->updateOrInsert(['role' => $role, 'permission_id' => $all[$name]], ['created_at' => now(), 'updated_at' => now()]);
        $this->command?->info('RBAC permissions berhasil di-seed untuk '.count($roles).' role.');
    }
}

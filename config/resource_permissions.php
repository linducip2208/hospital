<?php

/*
 * Peta otorisasi resource web → permission.
 * Dipakai Middleware RequireResourcePermission + PermissionSeeder
 * (satu sumber kebenaran).
 *
 * view_roles/manage_roles = role yang diberi hibah saat seeder dijalankan.
 * Middleware hanya menegakkan bila nama permission ADA di DB (fail-open),
 * sehingga instalasi lama tidak terkunci sebelum seeder dijalankan.
 * admin & developer selalu lolos (konsisten User::hasPermission).
 * manage([]) kosong = hanya admin/developer.
 */
$all = ['director', 'doctor', 'nurse', 'midwife', 'pharmacist', 'lab_technician', 'cashier', 'finance', 'HR', 'staff'];
$clinical = ['doctor', 'nurse', 'midwife'];

return [
    'queues' => ['view' => 'queues.view', 'manage' => 'queues.manage', 'view_roles' => $all, 'manage_roles' => ['staff', 'nurse', 'doctor', 'cashier', 'midwife']],
    'vital-signs' => ['view' => 'vital-signs.view', 'manage' => 'vital-signs.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'nursing-cares' => ['view' => 'nursing-cares.view', 'manage' => 'nursing-cares.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'medication-administrations' => ['view' => 'medication-administrations.view', 'manage' => 'medication-administrations.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['pharmacist'])],
    'diet-orders' => ['view' => 'diet-orders.view', 'manage' => 'diet-orders.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'shift-handovers' => ['view' => 'shift-handovers.view', 'manage' => 'shift-handovers.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'nurse-assignments' => ['view' => 'nurse-assignments.view', 'manage' => 'nurse-assignments.manage', 'view_roles' => $all, 'manage_roles' => ['doctor', 'nurse']],

    'emergencies' => ['view' => 'emergencies.view', 'manage' => 'emergencies.manage', 'view_roles' => $all, 'manage_roles' => $clinical],
    'code-blue-activations' => ['view' => 'code-blue-activations.view', 'manage' => 'code-blue-activations.manage', 'view_roles' => $all, 'manage_roles' => $clinical],
    'icu-monitorings' => ['view' => 'icu-monitorings.view', 'manage' => 'icu-monitorings.manage', 'view_roles' => $all, 'manage_roles' => $clinical],
    'surgeries' => ['view' => 'surgeries.view', 'manage' => 'surgeries.manage', 'view_roles' => $all, 'manage_roles' => ['doctor', 'nurse']],
    'informed-consents' => ['view' => 'informed-consents.view', 'manage' => 'informed-consents.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'referrals' => ['view' => 'referrals.view', 'manage' => 'referrals.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'telemedicine-sessions' => ['view' => 'telemedicine-sessions.view', 'manage' => 'telemedicine-sessions.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'discharge-summaries' => ['view' => 'discharge-summaries.view', 'manage' => 'discharge-summaries.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'medical-certificates' => ['view' => 'medical-certificates.view', 'manage' => 'medical-certificates.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'odontograms' => ['view' => 'odontograms.view', 'manage' => 'odontograms.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'clinical-pathways' => ['view' => 'clinical-pathways.view', 'manage' => 'clinical-pathways.manage', 'view_roles' => $all, 'manage_roles' => ['doctor', 'nurse']],

    'anc-records' => ['view' => 'anc-records.view', 'manage' => 'anc-records.manage', 'view_roles' => $all, 'manage_roles' => ['midwife', 'doctor', 'nurse']],
    'partographs' => ['view' => 'partographs.view', 'manage' => 'partographs.manage', 'view_roles' => $all, 'manage_roles' => ['midwife', 'doctor', 'nurse']],
    'postnatal-records' => ['view' => 'postnatal-records.view', 'manage' => 'postnatal-records.manage', 'view_roles' => $all, 'manage_roles' => ['midwife', 'doctor', 'nurse']],
    'baby-immunizations' => ['view' => 'baby-immunizations.view', 'manage' => 'baby-immunizations.manage', 'view_roles' => $all, 'manage_roles' => ['midwife', 'doctor', 'nurse']],
    'maternities' => ['view' => 'maternities.view', 'manage' => 'maternities.manage', 'view_roles' => $all, 'manage_roles' => ['midwife', 'doctor', 'nurse']],

    'rooms' => ['view' => 'rooms.view', 'manage' => 'rooms.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'hospital-beds' => ['view' => 'hospital-beds.view', 'manage' => 'hospital-beds.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'ambulances' => ['view' => 'ambulances.view', 'manage' => 'ambulances.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'ambulance-calls' => ['view' => 'ambulance-calls.view', 'manage' => 'ambulance-calls.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],

    'drugs' => ['view' => 'drugs.view', 'manage' => 'drugs.manage', 'view_roles' => $all, 'manage_roles' => ['pharmacist']],
    'dispensings' => ['view' => 'dispensings.view', 'manage' => 'dispensings.manage', 'view_roles' => $all, 'manage_roles' => ['pharmacist']],
    'drug-supply-orders' => ['view' => 'drug-supply-orders.view', 'manage' => 'drug-supply-orders.manage', 'view_roles' => $all, 'manage_roles' => ['pharmacist', 'finance']],
    'drug-destructions' => ['view' => 'drug-destructions.view', 'manage' => 'drug-destructions.manage', 'view_roles' => $all, 'manage_roles' => ['pharmacist']],

    'vendors' => ['view' => 'vendors.view', 'manage' => 'vendors.manage', 'view_roles' => $all, 'manage_roles' => ['staff', 'finance']],
    'assets' => ['view' => 'assets.view', 'manage' => 'assets.manage', 'view_roles' => $all, 'manage_roles' => ['staff', 'finance']],
    'purchase-orders' => ['view' => 'purchase-orders.view', 'manage' => 'purchase-orders.manage', 'view_roles' => $all, 'manage_roles' => ['staff', 'finance']],
    'equipment-calibrations' => ['view' => 'equipment-calibrations.view', 'manage' => 'equipment-calibrations.manage', 'view_roles' => $all, 'manage_roles' => ['staff', 'finance']],
    'equipment-maintenances' => ['view' => 'equipment-maintenances.view', 'manage' => 'equipment-maintenances.manage', 'view_roles' => $all, 'manage_roles' => ['staff', 'finance']],
    'medical-wastes' => ['view' => 'medical-wastes.view', 'manage' => 'medical-wastes.manage', 'view_roles' => $all, 'manage_roles' => ['nurse', 'staff']],

    'patient-screenings' => ['view' => 'patient-screenings.view', 'manage' => 'patient-screenings.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'patient-feedbacks' => ['view' => 'patient-feedbacks.view', 'manage' => 'patient-feedbacks.manage', 'view_roles' => $all, 'manage_roles' => ['staff']],
    'patient-safety-incidents' => ['view' => 'patient-safety-incidents.view', 'manage' => 'patient-safety-incidents.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'infection-surveillances' => ['view' => 'infection-surveillances.view', 'manage' => 'infection-surveillances.manage', 'view_roles' => $all, 'manage_roles' => array_merge($clinical, ['staff'])],
    'blood-donations' => ['view' => 'blood-donations.view', 'manage' => 'blood-donations.manage', 'view_roles' => $all, 'manage_roles' => ['doctor', 'nurse', 'lab_technician', 'staff']],

    'bills' => ['view' => 'billing.view', 'manage' => 'billing.manage', 'view_roles' => null, 'manage_roles' => null],
    'invoices' => ['view' => 'billing.view', 'manage' => 'billing.manage', 'view_roles' => null, 'manage_roles' => null],
    'cost-estimates' => ['view' => 'billing.view', 'manage' => 'billing.manage', 'view_roles' => null, 'manage_roles' => null],
    'journal-entries' => ['view' => 'accounting.view', 'manage' => 'accounting.post', 'view_roles' => null, 'manage_roles' => null],
    'chart-of-accounts' => ['view' => 'accounting.view', 'manage' => 'accounting.post', 'view_roles' => null, 'manage_roles' => null],

    'departments' => ['view' => 'departments.view', 'manage' => 'departments.manage', 'view_roles' => $all, 'manage_roles' => []],
    'polyclinics' => ['view' => 'polyclinics.view', 'manage' => 'polyclinics.manage', 'view_roles' => $all, 'manage_roles' => []],
    'employees' => ['view' => 'employees.view', 'manage' => 'employees.manage', 'view_roles' => ['admin', 'developer', 'HR', 'finance', 'director'], 'manage_roles' => ['HR']],
    'attendances' => ['view' => 'attendances.view', 'manage' => 'attendances.manage', 'view_roles' => ['admin', 'developer', 'HR', 'finance', 'director'], 'manage_roles' => ['HR', 'staff']],
    'leaves' => ['view' => 'leaves.view', 'manage' => 'leaves.manage', 'view_roles' => ['admin', 'developer', 'HR', 'finance', 'director'], 'manage_roles' => ['HR']],
    'staff-schedules' => ['view' => 'staff-schedules.view', 'manage' => 'staff-schedules.manage', 'view_roles' => $all, 'manage_roles' => ['HR', 'staff']],
    'salaries' => ['view' => 'salaries.view', 'manage' => 'salaries.manage', 'view_roles' => ['admin', 'developer', 'finance', 'HR', 'director'], 'manage_roles' => ['HR', 'finance']],
];

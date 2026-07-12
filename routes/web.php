<?php

use App\Http\Controllers\AmbulanceCallController;
use App\Http\Controllers\AmbulanceController;
use App\Http\Controllers\AncRecordController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BabyImmunizationController;
use App\Http\Controllers\BloodDonationController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ClinicalPathwayController;
use App\Http\Controllers\CodeBlueActivationController;
use App\Http\Controllers\CostEstimateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DietOrderController;
use App\Http\Controllers\DischargeSummaryController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\DrugDestructionController;
use App\Http\Controllers\DrugSupplyOrderController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EquipmentMaintenanceController;
use App\Http\Controllers\HospitalBedController;
use App\Http\Controllers\IcuMonitoringController;
use App\Http\Controllers\InfectionSurveillanceController;
use App\Http\Controllers\InformedConsentController;
use App\Http\Controllers\InsuranceClaimController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MaternityController;
use App\Http\Controllers\MedicalCertificateController;
use App\Http\Controllers\MedicationAdministrationController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\NurseAssignmentController;
use App\Http\Controllers\NursingCareController;
use App\Http\Controllers\OdontogramController;
use App\Http\Controllers\PageContentController;
use App\Http\Controllers\PartographController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientFeedbackController;
use App\Http\Controllers\PatientSafetyIncidentController;
use App\Http\Controllers\PatientScreeningController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PolyclinicController;
use App\Http\Controllers\PostnatalRecordController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\RadiologyController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShiftHandoverController;
use App\Http\Controllers\StaffScheduleController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\TelemedicineSessionController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VitalSignsController;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $stats = Cache::remember('welcome.stats', now()->addHour(), function () {
        return [
            'patients' => Patient::count(),
            'doctors'  => Doctor::where('status', 'active')->count(),
            'polys'    => Polyclinic::where('is_active', true)->count(),
        ];
    });

    return view('welcome', compact('stats'));
});

// Public documentation
Route::get('/docs', fn () => view('docs'))->name('docs');

// License pairing wizard v3
require base_path('routes/pair.php');

// Authentication
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);

    // Doctors
    Route::resource('doctors', DoctorController::class);

    // Treatments
    Route::resource('treatments', TreatmentController::class);

    // Appointments
    Route::post('/appointments/{appointment}/status/{status}', [AppointmentController::class, 'status'])->name('appointments.status');
    Route::resource('appointments', AppointmentController::class);

    // Medical Records
    Route::resource('medical-records', MedicalRecordController::class);

    // Payments
    Route::resource('payments', PaymentController::class);

    // Pharmacy / Drugs
    Route::resource('drugs', DrugController::class);

    // Rooms / Inpatient
    Route::resource('rooms', RoomController::class);

    // User Management
    Route::resource('users', UserController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Tutorial
    Route::get('/tutorial', [TutorialController::class, 'index'])->name('tutorial');

    // Polyclinics
    Route::resource('polyclinics', PolyclinicController::class);

    // Queues / Antrian
    Route::post('/queues/{queue}/call', [QueueController::class, 'call'])->name('queues.call');
    Route::post('/queues/{queue}/complete', [QueueController::class, 'complete'])->name('queues.complete');
    Route::resource('queues', QueueController::class);

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/satusehat', [SettingsController::class, 'satusehat'])->name('settings.satusehat');
    Route::match(['put', 'post'], '/settings/satusehat', [SettingsController::class, 'satusehatUpdate'])->name('settings.satusehat.update');
    Route::post('/settings/satusehat/test', [SettingsController::class, 'satusehatTest'])->name('settings.satusehat.test');
    Route::post('/settings/satusehat/webhook-test', [SettingsController::class, 'satusehatWebhookTest'])->name('settings.satusehat.webhook.test');
    Route::get('/settings/bpjs', [SettingsController::class, 'bpjs'])->name('settings.bpjs');
    Route::match(['put', 'post'], '/settings/bpjs', [SettingsController::class, 'bpjsUpdate'])->name('settings.bpjs.update');
    Route::get('/settings/branding', [SettingsController::class, 'branding'])->name('settings.branding');
    Route::post('/settings/branding', [SettingsController::class, 'brandingUpdate'])->name('settings.branding.update');

    // Laboratorium
    Route::resource('lab-tests', LabTestController::class);

    // Radiologi
    Route::resource('radiologies', RadiologyController::class);

    // Ruang Bersalin / Maternity
    Route::resource('maternities', MaternityController::class);

    // Nurse Assignments / Perawat & Bidan
    Route::resource('nurse-assignments', NurseAssignmentController::class);

    // IGD / Emergency
    Route::resource('emergencies', EmergencyController::class);

    // Referrals / Rujukan
    Route::resource('referrals', ReferralController::class);

    // Surgeries / OK
    Route::resource('surgeries', SurgeryController::class);

    // Blood Donations / Bank Darah
    Route::resource('blood-donations', BloodDonationController::class);

    // Staff Schedules / Jadwal Staff
    Route::resource('staff-schedules', StaffScheduleController::class);

    // Ambulances / Armada
    Route::resource('ambulances', AmbulanceController::class);

    // Ambulance Calls / Panggilan
    Route::resource('ambulance-calls', AmbulanceCallController::class);

    // CMS Landing Page — hanya developer & admin yang bisa akses
    Route::middleware('role:developer,admin')->group(function () {
        Route::get('/cms', [PageContentController::class, 'index'])->name('cms.index');
        Route::get('/cms/{pageContent}/edit', [PageContentController::class, 'edit'])->name('cms.edit');
        Route::put('/cms/{pageContent}', [PageContentController::class, 'update'])->name('cms.update');
        Route::post('/cms/{pageContent}/toggle', [PageContentController::class, 'toggle'])->name('cms.toggle');
        Route::post('/cms/{pageContent}/reset', [PageContentController::class, 'reset'])->name('cms.reset');
    });

    // Keperawatan ERP
    Route::resource('vital-signs', VitalSignsController::class);
    Route::resource('medication-administrations', MedicationAdministrationController::class);
    Route::resource('nursing-cares', NursingCareController::class);
    Route::resource('shift-handovers', ShiftHandoverController::class);

    // Kebidanan ERP
    Route::resource('anc-records', AncRecordController::class);
    Route::resource('partographs', PartographController::class);
    Route::resource('postnatal-records', PostnatalRecordController::class);
    Route::resource('baby-immunizations', BabyImmunizationController::class);

    // HR & Payroll
    Route::resource('employees', EmployeeController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::post('/salaries/{salary}/approve', [SalaryController::class, 'approve'])->name('salaries.approve');
    Route::post('/salaries/{salary}/pay', [SalaryController::class, 'pay'])->name('salaries.pay');
    Route::resource('salaries', SalaryController::class);
    Route::resource('departments', DepartmentController::class);

    // Cuti / Leave
    Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::resource('leaves', LeaveController::class);

    // Asset Management
    Route::resource('assets', AssetController::class);

    // Procurement / Purchase Order
    Route::resource('purchase-orders', PurchaseOrderController::class);

    // Finance / Accounting
    Route::resource('chart-of-accounts', ChartOfAccountController::class);
    Route::post('/journal-entries/{journal_entry}/post', [JournalEntryController::class, 'post'])->name('journal-entries.post');
    Route::resource('journal-entries', JournalEntryController::class);

    // ============================================================
    // Surat & Cetak Dokumen
    // ============================================================

    // Surat Keterangan Medis (sakit/sehat/bebas narkoba/hamil/lahir/kematian/visum)
    Route::get('medical-certificates/{medical_certificate}/print', [MedicalCertificateController::class, 'print'])->name('medical-certificates.print');
    Route::resource('medical-certificates', MedicalCertificateController::class);

    // Surat Persetujuan / Penolakan / APS
    Route::get('informed-consents/{informed_consent}/print', [InformedConsentController::class, 'print'])->name('informed-consents.print');
    Route::resource('informed-consents', InformedConsentController::class);

    // Resep Dokter
    Route::get('prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])->name('prescriptions.print');
    Route::get('prescriptions/{prescription}/labels', [PrescriptionController::class, 'printLabels'])->name('prescriptions.print-labels');
    Route::resource('prescriptions', PrescriptionController::class);

    // Surat Pesanan Obat
    Route::get('drug-supply-orders/{drug_supply_order}/print', [DrugSupplyOrderController::class, 'print'])->name('drug-supply-orders.print');
    Route::resource('drug-supply-orders', DrugSupplyOrderController::class);

    // Berita Acara Pemusnahan Obat
    Route::get('drug-destructions/{drug_destruction}/print', [DrugDestructionController::class, 'print'])->name('drug-destructions.print');
    Route::resource('drug-destructions', DrugDestructionController::class);

    // Estimasi Biaya
    Route::get('cost-estimates/{cost_estimate}/print', [CostEstimateController::class, 'print'])->name('cost-estimates.print');
    Route::resource('cost-estimates', CostEstimateController::class);

    // Klaim Asuransi
    Route::get('insurance-claims/{insurance_claim}/print', [InsuranceClaimController::class, 'print'])->name('insurance-claims.print');
    Route::resource('insurance-claims', InsuranceClaimController::class);

    // Resume Medis Pulang
    Route::get('discharge-summaries/{discharge_summary}/print', [DischargeSummaryController::class, 'print'])->name('discharge-summaries.print');
    Route::resource('discharge-summaries', DischargeSummaryController::class);

    // Skrining Pasien
    Route::get('patient-screenings/{patient_screening}/print', [PatientScreeningController::class, 'print'])->name('patient-screenings.print');
    Route::resource('patient-screenings', PatientScreeningController::class);

    // ============================================================
    // Fitur Lanjutan Hospital
    // ============================================================
    Route::resource('hospital-beds', HospitalBedController::class)->except(['show']);
    Route::resource('patient-safety-incidents', PatientSafetyIncidentController::class);
    Route::resource('code-blue-activations', CodeBlueActivationController::class);
    Route::resource('diet-orders', DietOrderController::class);
    Route::resource('odontograms', OdontogramController::class);
    Route::resource('patient-feedbacks', PatientFeedbackController::class);
    Route::resource('clinical-pathways', ClinicalPathwayController::class);
    Route::resource('infection-surveillances', InfectionSurveillanceController::class);
    Route::resource('equipment-maintenances', EquipmentMaintenanceController::class);
    Route::resource('icu-monitorings', IcuMonitoringController::class);
    Route::resource('telemedicine-sessions', TelemedicineSessionController::class);

    // Print views untuk model existing
    Route::get('patients/{patient}/print/card', [PatientController::class, 'printCard'])->name('patients.print-card');
    Route::get('patients/{patient}/print/wristband', [PatientController::class, 'printWristband'])->name('patients.print-wristband');
    Route::get('patients/{patient}/print/stickers', [PatientController::class, 'printStickers'])->name('patients.print-stickers');
    Route::get('referrals/{referral}/print', [ReferralController::class, 'print'])->name('referrals.print');
    Route::get('lab-tests/{lab_test}/print', [LabTestController::class, 'print'])->name('lab-tests.print');
    Route::get('radiologies/{radiology}/print', [RadiologyController::class, 'print'])->name('radiologies.print');
    Route::get('surgeries/{surgery}/print', [App\Http\Controllers\SurgeryController::class, 'print'])->name('surgeries.print');
    Route::get('payments/{payment}/print/receipt', [PaymentController::class, 'printReceipt'])->name('payments.print-receipt');
    Route::get('payments/{payment}/print/bill', [PaymentController::class, 'printBill'])->name('payments.print-bill');
    Route::get('medical-records/{medical_record}/print', [MedicalRecordController::class, 'print'])->name('medical-records.print');
    Route::get('ambulance-calls/{ambulance_call}/print', [AmbulanceCallController::class, 'print'])->name('ambulance-calls.print');
    Route::get('emergencies/{emergency}/print/triage', [EmergencyController::class, 'printTriage'])->name('emergencies.print-triage');
});
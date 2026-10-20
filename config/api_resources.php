<?php

use App\Models\Admission;
use App\Models\Ambulance;
use App\Models\AmbulanceCall;
use App\Models\AncRecord;
use App\Models\Asset;
use App\Models\Attendance;
use App\Models\BabyImmunization;
use App\Models\Bill;
use App\Models\BloodDonation;
use App\Models\ChartOfAccount;
use App\Models\ClinicalOrder;
use App\Models\ClinicalPathway;
use App\Models\CodeBlueActivation;
use App\Models\CostEstimate;
use App\Models\Department;
use App\Models\DietOrder;
use App\Models\DischargeSummary;
use App\Models\Dispensing;
use App\Models\Drug;
use App\Models\DrugDestruction;
use App\Models\DrugSupplyOrder;
use App\Models\Emergency;
use App\Models\Employee;
use App\Models\Encounter;
use App\Models\EquipmentCalibration;
use App\Models\EquipmentMaintenance;
use App\Models\HospitalBed;
use App\Models\IcuMonitoring;
use App\Models\InfectionSurveillance;
use App\Models\InformedConsent;
use App\Models\InsuranceClaim;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\LabTest;
use App\Models\Leave;
use App\Models\Maternity;
use App\Models\MedicalCertificate;
use App\Models\MedicalWaste;
use App\Models\MedicationAdministration;
use App\Models\NurseAssignment;
use App\Models\NursingCare;
use App\Models\Odontogram;
use App\Models\Partograph;
use App\Models\PatientFeedback;
use App\Models\PatientSafetyIncident;
use App\Models\PatientScreening;
use App\Models\Polyclinic;
use App\Models\PostnatalRecord;
use App\Models\Prescription;
use App\Models\PurchaseOrder;
use App\Models\Queue;
use App\Models\Radiology;
use App\Models\Referral;
use App\Models\Room;
use App\Models\Salary;
use App\Models\ShiftHandover;
use App\Models\StaffSchedule;
use App\Models\Surgery;
use App\Models\TelemedicineSession;
use App\Models\Vendor;
use App\Models\VitalSignsRecord;

/*
 * Modul dengan 'read_roles'/'write_roles' dibatasi per peran (selain itu:
 * semua staf boleh baca, semua staf boleh tulis). admin & developer
 * selalu lolos. Token pasien selalu ditolak (403).
 */
return [
    'queues' => ['model' => Queue::class],
    'encounters' => ['model' => Encounter::class],
    'admissions' => ['model' => Admission::class],
    'vital-signs' => ['model' => VitalSignsRecord::class],
    'nursing-cares' => ['model' => NursingCare::class],
    'medication-administrations' => ['model' => MedicationAdministration::class],
    'diet-orders' => ['model' => DietOrder::class],
    'icu-monitorings' => ['model' => IcuMonitoring::class],
    'prescriptions' => ['model' => Prescription::class],
    'dispensings' => ['model' => Dispensing::class],
    'lab-tests' => ['model' => LabTest::class],
    'radiologies' => ['model' => Radiology::class],
    'emergencies' => ['model' => Emergency::class],
    'surgeries' => ['model' => Surgery::class],
    'referrals' => ['model' => Referral::class],
    'telemedicine-sessions' => ['model' => TelemedicineSession::class],
    'informed-consents' => ['model' => InformedConsent::class],
    'discharge-summaries' => ['model' => DischargeSummary::class],
    'medical-certificates' => ['model' => MedicalCertificate::class],
    'odontograms' => ['model' => Odontogram::class],
    'clinical-orders' => ['model' => ClinicalOrder::class],
    'clinical-pathways' => ['model' => ClinicalPathway::class],
    'anc-records' => ['model' => AncRecord::class],
    'partographs' => ['model' => Partograph::class],
    'postnatal-records' => ['model' => PostnatalRecord::class],
    'baby-immunizations' => ['model' => BabyImmunization::class],
    'maternities' => ['model' => Maternity::class],
    'rooms' => ['model' => Room::class],
    'hospital-beds' => ['model' => HospitalBed::class],
    'drugs' => ['model' => Drug::class],
    'drug-supply-orders' => ['model' => DrugSupplyOrder::class],
    'drug-destructions' => ['model' => DrugDestruction::class],
    'purchase-orders' => ['model' => PurchaseOrder::class],
    'vendors' => ['model' => Vendor::class],
    'assets' => ['model' => Asset::class],
    'equipment-calibrations' => ['model' => EquipmentCalibration::class],
    'equipment-maintenances' => ['model' => EquipmentMaintenance::class],
    'medical-wastes' => ['model' => MedicalWaste::class],
    'ambulances' => ['model' => Ambulance::class],
    'ambulance-calls' => ['model' => AmbulanceCall::class],
    'code-blue-activations' => ['model' => CodeBlueActivation::class],
    'patient-screenings' => ['model' => PatientScreening::class],
    'patient-feedbacks' => ['model' => PatientFeedback::class],
    'patient-safety-incidents' => ['model' => PatientSafetyIncident::class],
    'blood-donations' => ['model' => BloodDonation::class],
    'cost-estimates' => ['model' => CostEstimate::class, 'write_roles' => ['admin', 'developer', 'finance', 'cashier']],
    'bills' => ['model' => Bill::class, 'write_roles' => ['admin', 'developer', 'finance', 'cashier']],
    'invoices' => ['model' => Invoice::class, 'write_roles' => ['admin', 'developer', 'finance', 'cashier']],
    'journal-entries' => ['model' => JournalEntry::class, 'read_roles' => ['admin', 'developer', 'finance', 'director'], 'write_roles' => ['admin', 'developer', 'finance']],
    'chart-of-accounts' => ['model' => ChartOfAccount::class, 'read_roles' => ['admin', 'developer', 'finance', 'director'], 'write_roles' => ['admin', 'developer', 'finance']],
    'departments' => ['model' => Department::class],
    'polyclinics' => ['model' => Polyclinic::class],
    'employees' => ['model' => Employee::class, 'read_roles' => ['admin', 'developer', 'HR', 'finance', 'director'], 'write_roles' => ['admin', 'developer', 'HR']],
    'attendances' => ['model' => Attendance::class],
    'leaves' => ['model' => Leave::class],
    'staff-schedules' => ['model' => StaffSchedule::class],
    'shift-handovers' => ['model' => ShiftHandover::class],
    'salaries' => ['model' => Salary::class, 'read_roles' => ['admin', 'developer', 'finance', 'HR', 'director'], 'write_roles' => ['admin', 'developer', 'finance', 'HR']],
    'nurse-assignments' => ['model' => NurseAssignment::class],
    'infection-surveillances' => ['model' => InfectionSurveillance::class],
];

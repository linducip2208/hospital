<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\InsuranceClaim;
use App\Models\Emergency;
use App\Models\HospitalBed;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    public function summary(Carbon $from, Carbon $to): array
    {
        return [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::count(),
            'total_appointments' => Appointment::whereBetween('appointment_date', [$from, $to])->count(),
            'total_payments' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$from, $to])->sum('amount'),
            'total_drugs' => Drug::count(),
            'total_rooms' => Room::count(),
        ];
    }

    public function appointmentStatus(Carbon $from, Carbon $to): Collection
    {
        return Appointment::selectRaw('status, COUNT(*) total')
            ->whereBetween('appointment_date', [$from, $to])
            ->groupBy('status')->get();
    }

    public function revenue(Carbon $from, Carbon $to, string $groupBy = 'month'): Collection
    {
        $query = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$from, $to]);

        return match ($groupBy) {
            'day' => $query->selectRaw('SUM(amount) total, DATE(created_at) period, DAY(created_at) day, MONTH(created_at) month, YEAR(created_at) year')
                ->groupByRaw('DATE(created_at), DAY(created_at), MONTH(created_at), YEAR(created_at)')
                ->orderByRaw('DATE(created_at)')->get(),
            'year' => $query->selectRaw('SUM(amount) total, YEAR(created_at) year')
                ->groupByRaw('YEAR(created_at)')->orderByRaw('YEAR(created_at)')->get(),
            default => $query->selectRaw('SUM(amount) total, MONTH(created_at) month, YEAR(created_at) year')
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at), MONTH(created_at)')->get(),
        };
    }

    public function topTreatments(Carbon $from, Carbon $to, int $limit = 5): Collection
    {
        return Treatment::selectRaw('treatments.name, COUNT(appointments.id) total')
            ->leftJoin('appointments', function ($join) use ($from, $to) {
                $join->on('appointments.treatment_id', '=', 'treatments.id')
                    ->whereBetween('appointments.appointment_date', [$from, $to]);
            })
            ->groupBy('treatments.id', 'treatments.name')
            ->orderByDesc('total')
            ->limit($limit)->get();
    }

    public function recentPayments(Carbon $from, Carbon $to, int $limit = 10): Collection
    {
        return Payment::with('patient', 'appointment.patient')
            ->whereBetween('created_at', [$from, $to])
            ->latest()->limit($limit)->get();
    }

    /** Data lengkap untuk halaman + PDF. */
    public function financialReport(Carbon $from, Carbon $to, string $groupBy = 'month'): array
    {
        return [
            'from' => $from,
            'to' => $to,
            'groupBy' => $groupBy,
            'stats' => $this->summary($from, $to),
            'appointmentStatus' => $this->appointmentStatus($from, $to),
            'revenue' => $this->revenue($from, $to, $groupBy),
            'topTreatments' => $this->topTreatments($from, $to),
            'recentPayments' => $this->recentPayments($from, $to),
        ];
    }

    /** Cost center: P&L / pendapatan per departemen. */
    public function costCenter(Carbon $from, Carbon $to): Collection
    {
        return Department::leftJoin('payments', function ($join) use ($from, $to) {
            $join->on('payments.department_id', '=', 'departments.id')
                ->where('payments.status', 'completed')
                ->whereBetween('payments.created_at', [$from, $to]);
        })
            ->selectRaw('departments.name, COUNT(payments.id) as tx, COALESCE(SUM(payments.amount),0) as revenue')
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('revenue')
            ->get();
    }

    /** Billing breakdown per payer: umum / BPJS / asuransi. */
    public function billingBreakdown(Carbon $from, Carbon $to): Collection
    {
        return Payment::where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('payer_type, COUNT(*) as tx, SUM(amount) as revenue')
            ->groupBy('payer_type')
            ->get();
    }

    /** Case-mix: distribusi klaim per kelompok INA-CBG / diagnosis. */
    public function caseMix(Carbon $from, Carbon $to, int $limit = 10): Collection
    {
        return InsuranceClaim::whereBetween('service_date', [$from, $to])
            ->selectRaw('COALESCE(NULLIF(inacbg_code, ""), diagnosis_code) as grp, COUNT(*) as cases, SUM(claimed_amount) as claimed, SUM(approved_amount) as approved')
            ->groupBy('grp')
            ->orderByDesc('cases')
            ->limit($limit)
            ->get();
    }

    public function financeAdvanced(Carbon $from, Carbon $to): array
    {
        return [
            'from' => $from,
            'to' => $to,
            'costCenter' => $this->costCenter($from, $to),
            'billingBreakdown' => $this->billingBreakdown($from, $to),
            'caseMix' => $this->caseMix($from, $to),
        ];
    }

    public function operationalReport(Carbon $from, Carbon $to): array
    {
        $appointments = Appointment::whereBetween('appointment_date', [$from, $to]);
        $emergencies = Emergency::whereBetween('created_at', [$from, $to]);
        $labTests = LabTest::whereBetween('created_at', [$from, $to]);

        return [
            'from' => $from,
            'to' => $to,
            'stats' => [
                'appointments' => (clone $appointments)->count(),
                'completed_appointments' => (clone $appointments)->where('status', 'completed')->count(),
                'emergencies' => (clone $emergencies)->count(),
                'pending_lab_tests' => (clone $labTests)->whereIn('status', ['pending', 'in_progress'])->count(),
                'occupied_beds' => HospitalBed::where('status', 'occupied')->count(),
                'low_stock_drugs' => Drug::where('is_active', true)->where('stock', '<=', 10)->count(),
            ],
            'appointmentStatus' => (clone $appointments)->selectRaw('status, COUNT(*) total')->groupBy('status')->get(),
            'emergencyStatus' => (clone $emergencies)->selectRaw('status, COUNT(*) total')->groupBy('status')->get(),
            'labStatus' => (clone $labTests)->selectRaw('status, COUNT(*) total')->groupBy('status')->get(),
            'recentAppointments' => (clone $appointments)->with(['patient:id,name', 'doctor:id,name'])
                ->latest('appointment_date')->limit(12)->get(),
        ];
    }
}

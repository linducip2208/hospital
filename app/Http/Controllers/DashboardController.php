<?php

namespace App\Http\Controllers;

use App\Models\Ambulance;
use App\Models\AmbulanceCall;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\Emergency;
use App\Models\HospitalBed;
use App\Models\InsuranceClaim;
use App\Models\LabTest;
use App\Models\MedicationAdministration;
use App\Models\Patient;
use App\Models\PatientFeedback;
use App\Models\Payment;
use App\Models\Polyclinic;
use App\Models\Prescription;
use App\Models\Queue;
use App\Models\Radiology;
use App\Models\Referral;
use App\Models\Room;
use App\Models\StaffSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = today();
        $now = now();
        $role = auth()->user()?->role;

        // Dashboard fokus per-role
        if (in_array($role, ['doctor', 'nurse', 'midwife', 'cashier', 'pharmacist'], true)) {
            return $this->roleDashboard($role, $today, $now);
        }

        // Cache hanya data agregat primitif (array/int/string) — aman di-serialize/unserialize.
        // Eloquent collection TIDAK di-cache (fragile saat unserialize) — query live tiap request.
        $aggregates = Cache::remember('dashboard.aggregates', now()->addMinutes(5), function () use ($today, $now) {
            return $this->buildAggregates($today, $now);
        });

        // Live queries — ringan (max ~8 record per query, sudah eager-load relasi).
        $live = $this->buildLive($today);

        // Widget klinis & operasional lanjutan (live, ringan).
        $advanced = $this->buildAdvanced($today, $now);

        return view('dashboard.index', array_merge($aggregates, $live, $advanced));
    }

    /**
     * Dashboard fokus sesuai peran pengguna.
     */
    private function roleDashboard(string $role, Carbon $today, Carbon $now): View
    {
        $user = auth()->user();
        $doctorId = $user->doctor?->id;

        $data = ['role' => $role, 'user' => $user];

        if ($role === 'doctor') {
            $data += [
                'myAppointmentsToday' => Appointment::with('patient', 'polyclinic')
                    ->when($doctorId, fn ($q) => $q->where('doctor_id', $doctorId))
                    ->whereDate('appointment_date', $today)
                    ->orderBy('start_time')->get(),
                'myQueueWaiting' => Queue::when($doctorId, fn ($q) => $q->where('doctor_id', $doctorId))
                    ->where('status', 'waiting')->count(),
                'myPatientsMonth' => Appointment::when($doctorId, fn ($q) => $q->where('doctor_id', $doctorId))
                    ->whereMonth('appointment_date', $now->month)->distinct('patient_id')->count('patient_id'),
                'myLabPending' => LabTest::when($doctorId, fn ($q) => $q->where('doctor_id', $doctorId))
                    ->whereIn('status', ['requested', 'sample_collected', 'in_progress'])->count(),
                'myReferrals' => Referral::when($doctorId, fn ($q) => $q->where('doctor_id', $doctorId))
                    ->where('status', 'pending')->count(),
            ];
        } elseif (in_array($role, ['nurse', 'midwife'], true)) {
            $data += [
                'igdActive' => Emergency::whereIn('status', ['waiting', 'in_treatment', 'observation'])->count(),
                'triageRed' => Emergency::where('triage', 'red')->whereIn('status', ['waiting', 'in_treatment', 'observation'])->count(),
                'bedOccupied' => HospitalBed::where('status', 'occupied')->count(),
                'bedAvailable' => HospitalBed::where('status', 'available')->count(),
                'medsDueToday' => MedicationAdministration::whereDate('created_at', $today)->count(),
                'onDutyNow' => StaffSchedule::with('user')->whereDate('shift_date', $today)
                    ->where('shift_type', '!=', 'off')->take(10)->get(),
            ];
        } elseif ($role === 'cashier') {
            $data += [
                'paymentsToday' => Payment::whereDate('created_at', $today)->where('status', 'completed')->sum('amount'),
                'txCountToday' => Payment::whereDate('created_at', $today)->count(),
                'pendingPayments' => Payment::where('status', 'pending')->count(),
                'pendingAmount' => Payment::where('status', 'pending')->sum('amount'),
                'recentPayments' => Payment::with('patient')->latest()->take(10)->get(),
            ];
        } elseif ($role === 'pharmacist') {
            $data += [
                'lowStock' => Drug::where('stock', '<', 50)->count(),
                'totalDrugs' => Drug::count(),
                'prescriptionsToday' => Prescription::whereDate('created_at', $today)->count(),
                'lowStockDrugs' => Drug::where('stock', '<', 50)->orderBy('stock')->take(10)->get(),
            ];
        }

        return view('dashboard.role.'.($role === 'midwife' ? 'nurse' : $role), $data);
    }

    /**
     * Widget baru: klaim BPJS, rujukan, TAT lab/radiologi, triase IGD,
     * denah bed, roster shift, kepuasan pasien, notifikasi terkategori.
     */
    private function buildAdvanced(Carbon $today, Carbon $now): array
    {
        // ── Status Klaim BPJS/Asuransi ──
        $claimStats = [
            'draft' => InsuranceClaim::where('status', 'draft')->count(),
            'submitted' => InsuranceClaim::where('status', 'submitted')->count(),
            'approved' => InsuranceClaim::where('status', 'approved')->count(),
            'rejected' => InsuranceClaim::where('status', 'rejected')->count(),
            'paid' => InsuranceClaim::where('status', 'paid')->count(),
            'pending_amount' => (float) InsuranceClaim::whereIn('status', ['submitted', 'draft'])->sum('claimed_amount'),
        ];

        // ── Rujukan In/Out (referral) ──
        $referralStats = [
            'pending' => Referral::where('status', 'pending')->count(),
            'approved' => Referral::where('status', 'approved')->count(),
            'completed' => Referral::where('status', 'completed')->count(),
            'today' => Referral::whereDate('created_at', $today)->count(),
        ];

        // ── Turnaround Time Lab & Radiologi (rata-rata jam) ──
        $tatExpr = DB::connection()->getDriverName() === 'sqlite'
            ? 'AVG((julianday(result_date) - julianday(created_at)) * 24)'
            : 'AVG(TIMESTAMPDIFF(MINUTE, created_at, result_date) / 60)';
        $labTat = [
            'pending' => LabTest::whereIn('status', ['requested', 'sample_collected', 'in_progress'])->count(),
            'completed_today' => LabTest::where('status', 'completed')->whereDate('result_date', $today)->count(),
            'avg_hours' => round((float) LabTest::where('status', 'completed')->whereNotNull('result_date')->selectRaw("{$tatExpr} as t")->value('t'), 1),
        ];
        $radioTat = [
            'pending' => Radiology::whereIn('status', ['requested', 'in_progress'])->count(),
            'completed_today' => Radiology::where('status', 'completed')->whereDate('created_at', $today)->count(),
        ];

        // ── Triase IGD (level ESI: merah/kuning/hijau/hitam) ──
        $triage = [
            'red' => Emergency::where('triage', 'red')->whereIn('status', ['waiting', 'in_treatment', 'observation'])->count(),
            'yellow' => Emergency::where('triage', 'yellow')->whereIn('status', ['waiting', 'in_treatment', 'observation'])->count(),
            'green' => Emergency::where('triage', 'green')->whereIn('status', ['waiting', 'in_treatment', 'observation'])->count(),
            'black' => Emergency::where('triage', 'black')->whereDate('created_at', $today)->count(),
        ];
        $triageQueue = Emergency::with('patient')
            ->whereIn('status', ['waiting', 'in_treatment', 'observation'])
            ->orderByRaw("CASE triage WHEN 'red' THEN 1 WHEN 'yellow' THEN 2 WHEN 'green' THEN 3 WHEN 'black' THEN 4 ELSE 5 END")
            ->take(6)->get();

        // ── Denah Bed Real-time (per kamar) ──
        $bedBoard = HospitalBed::with('room')
            ->orderBy('room_id')->orderBy('bed_code')
            ->get()
            ->groupBy(fn ($b) => $b->room?->name ?? 'Tanpa Kamar');
        $bedSummary = [
            'available' => HospitalBed::where('status', 'available')->count(),
            'occupied' => HospitalBed::where('status', 'occupied')->count(),
            'cleaning' => HospitalBed::where('status', 'cleaning')->count(),
            'maintenance' => HospitalBed::whereIn('status', ['maintenance', 'blocked'])->count(),
            'reserved' => HospitalBed::where('status', 'reserved')->count(),
        ];

        // ── Roster: siapa sedang shift sekarang ──
        $onDutyNow = StaffSchedule::with('user')
            ->whereDate('shift_date', $today)
            ->where('shift_type', '!=', 'off')
            ->whereTime('start_time', '<=', $now->format('H:i:s'))
            ->whereTime('end_time', '>=', $now->format('H:i:s'))
            ->take(10)->get();
        $onDutyCount = $onDutyNow->count();

        // ── Kepuasan Pasien (fix "--") ──
        $feedbackAvg = round((float) PatientFeedback::whereMonth('created_at', $now->month)->avg('rating_overall'), 1);
        $feedbackCount = PatientFeedback::whereMonth('created_at', $now->month)->count();
        $feedbackRecommend = PatientFeedback::whereMonth('created_at', $now->month)->where('would_recommend', true)->count();
        $satisfaction = [
            'avg' => $feedbackAvg,
            'count' => $feedbackCount,
            'nps' => $feedbackCount > 0 ? round(($feedbackRecommend / $feedbackCount) * 100) : null,
        ];

        // ── Notifikasi terkategori ──
        $notifications = [
            'stok_obat' => Drug::where('stock', '<', 50)->count(),
            'klaim_pending' => $claimStats['submitted'] + $claimStats['draft'],
            'appointment_today' => Appointment::whereDate('appointment_date', $today)->whereIn('status', ['scheduled', 'confirmed'])->count(),
            'lab_pending' => $labTat['pending'],
            'igd_kritis' => $triage['red'],
            'rujukan_pending' => $referralStats['pending'],
        ];

        return compact(
            'claimStats', 'referralStats', 'labTat', 'radioTat',
            'triage', 'triageQueue', 'bedBoard', 'bedSummary',
            'onDutyNow', 'onDutyCount', 'satisfaction', 'notifications'
        );
    }

    /**
     * Bangun data agregat primitif (stats, charts) — aman untuk di-cache.
     */
    private function buildAggregates(Carbon $today, Carbon $now): array
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::where('status', 'active')->count(),
            'appointments_today' => Appointment::whereDate('appointment_date', $today)->count(),
            'appointments_month' => Appointment::whereMonth('appointment_date', $now->month)
                ->whereYear('appointment_date', $now->year)->count(),
            'revenue_month' => Payment::where('status', 'completed')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->sum('amount'),
            'total_users' => User::count(),
            'total_drugs' => Drug::count(),
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'emergency_today' => Emergency::whereDate('created_at', $today)->count(),
            'queue_active' => Queue::where('status', 'waiting')->count(),
            'ambulance_active' => Ambulance::where('status', 'available')->count(),
            // TODO: ganti jadi whereColumn('stock','<','min_stock') kalau kolom min_stock sudah ada
            'critical_drugs' => Drug::where('stock', '<', 50)->count(),
            'occupied_rooms' => Room::where('status', 'occupied')->count(),
            'patients_today' => Patient::whereDate('created_at', $today)->count(),
        ];

        $totalRooms = $stats['total_rooms'];
        $occupiedRooms = $stats['occupied_rooms'];
        $occupancyPct = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

        $patientChart = $this->getPatientChart();
        $revenueChart = $this->getRevenueChart();
        $polyclinicChart = $this->getPolyclinicChart();
        $hourlyChart = $this->getHourlyChart($today);
        $roomOccupancy = [
            'labels' => ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'ICU', 'NICU', 'OK'],
            'data' => $this->getOccupancyBreakdown(),
        ];

        return compact(
            'stats', 'occupancyPct',
            'patientChart', 'revenueChart', 'polyclinicChart', 'hourlyChart', 'roomOccupancy'
        );
    }

    /**
     * Bangun Eloquent collection — query live tiap request (TIDAK di-cache).
     * Alasan: cache → serialize Eloquent → unserialize sering korup attribute casts,
     * bikin `$model->appointment_date` jadi string (bukan Carbon). Lebih aman query ulang.
     */
    private function buildLive(Carbon $today): array
    {
        $recentAppointments = Appointment::with(['patient', 'doctor'])->latest()->take(5)->get();
        $recentPayments = Payment::with(['appointment.patient'])->latest()->take(5)->get();
        $recentPatients = Patient::latest()->take(5)->get();
        $liveQueues = Queue::with(['patient', 'polyclinic', 'doctor'])
            ->where('status', 'waiting')
            ->orderBy('queue_number')->take(8)->get();
        $ambulanceCalls = AmbulanceCall::with('ambulance')
            ->whereDate('created_at', $today)->latest()->take(5)->get();

        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $roomAlerts = Room::whereIn('status', ['occupied', 'maintenance'])
            ->orderBy('status')->get()
            ->map(function ($room) use ($occupiedRooms, $totalRooms) {
                $room->occupancy_pct = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

                return $room;
            });

        $polyclinics = Polyclinic::withCount(['queues' => function ($q) use ($today) {
            $q->whereDate('created_at', $today);
        }])->where('is_active', true)->get();

        return compact(
            'recentAppointments', 'recentPayments', 'recentPatients',
            'liveQueues', 'ambulanceCalls', 'roomAlerts', 'polyclinics'
        );
    }

    /**
     * Chart pasien 7 hari — SATU query agregat.
     */
    private function getPatientChart(): array
    {
        $start = now()->subDays(6)->startOfDay();
        $raw = Patient::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')->orderBy('d')
            ->pluck('c', 'd');

        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $key = $date->toDateString();
            $labels[] = $date->translatedFormat('D');
            $data[] = $raw[$key] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Chart pendapatan 6 bulan — SATU query agregat.
     */
    private function getRevenueChart(): array
    {
        $start = now()->subMonths(5)->startOfMonth();
        // SQLite pakai strftime, MySQL pakai DATE_FORMAT
        $monthExpr = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";
        $raw = Payment::where('status', 'completed')
            ->where('created_at', '>=', $start)
            ->selectRaw("{$monthExpr} as m, SUM(amount) as t")
            ->groupBy('m')->orderBy('m')
            ->pluck('t', 'm');

        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M');
            $data[] = (int) ($raw[$key] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Chart poli terpadat bulan ini.
     */
    private function getPolyclinicChart(): array
    {
        $clinics = Polyclinic::withCount(['queues as total' => function ($q) {
            $q->whereMonth('created_at', now()->month);
        }])->where('is_active', true)->orderByDesc('total')->take(6)->get();

        return [
            'labels' => $clinics->pluck('name')->toArray(),
            'data' => $clinics->pluck('total')->toArray(),
        ];
    }

    /**
     * Chart jam sibuk — DUA query agregat (appointment + queue).
     * Merge di PHP, fill 24 jam.
     */
    private function getHourlyChart(Carbon $today): array
    {
        // SQLite tidak punya HOUR() — pakai strftime('%H', col)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $hourApp = $isSqlite ? "CAST(strftime('%H', appointment_date) AS INTEGER)" : 'HOUR(appointment_date)';
        $hourQ = $isSqlite ? "CAST(strftime('%H', created_at) AS INTEGER)" : 'HOUR(created_at)';

        $appRaw = Appointment::whereDate('appointment_date', $today)
            ->selectRaw("{$hourApp} as h, COUNT(*) as c")
            ->groupBy('h')->pluck('c', 'h');

        $queueRaw = Queue::whereDate('created_at', $today)
            ->selectRaw("{$hourQ} as h, COUNT(*) as c")
            ->groupBy('h')->pluck('c', 'h');

        $labels = [];
        $data = [];
        for ($h = 0; $h < 24; $h++) {
            $labels[] = sprintf('%02d:00', $h);
            $data[] = ($appRaw[$h] ?? 0) + ($queueRaw[$h] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Okupansi per tipe kamar — satu query agregat.
     */
    private function getOccupancyBreakdown(): array
    {
        return Room::selectRaw("
            CASE
                WHEN room_type LIKE '%VIP%' OR room_type LIKE '%vip%' THEN 'VIP'
                WHEN room_type LIKE '%kelas 1%' OR room_type LIKE '%kelas1%' THEN 'Kelas 1'
                WHEN room_type LIKE '%kelas 2%' OR room_type LIKE '%kelas2%' THEN 'Kelas 2'
                WHEN room_type LIKE '%kelas 3%' OR room_type LIKE '%kelas3%' THEN 'Kelas 3'
                WHEN room_type LIKE '%ICU%' OR room_type LIKE '%icu%' THEN 'ICU'
                WHEN room_type LIKE '%NICU%' OR room_type LIKE '%nicu%' THEN 'NICU'
                WHEN room_type LIKE '%OK%' OR room_type LIKE '%operasi%' OR room_type LIKE '%bedah%' THEN 'OK'
                ELSE 'Lainnya'
            END as category,
            COUNT(*) as total,
            SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied
        ")->groupBy('category')->get()
            ->mapWithKeys(fn ($r) => [$r->category => $r->total > 0 ? round(($r->occupied / $r->total) * 100) : 0])
            ->toArray();
    }
}

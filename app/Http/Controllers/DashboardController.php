<?php

namespace App\Http\Controllers;

use App\Models\Ambulance;
use App\Models\AmbulanceCall;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\Emergency;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Polyclinic;
use App\Models\Queue;
use App\Models\Room;
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

        // Cache hanya data agregat primitif (array/int/string) — aman di-serialize/unserialize.
        // Eloquent collection TIDAK di-cache (fragile saat unserialize) — query live tiap request.
        $aggregates = Cache::remember('dashboard.aggregates', now()->addMinutes(5), function () use ($today, $now) {
            return $this->buildAggregates($today, $now);
        });

        // Live queries — ringan (max ~8 record per query, sudah eager-load relasi).
        $live = $this->buildLive($today);

        return view('dashboard.index', array_merge($aggregates, $live));
    }

    /**
     * Bangun data agregat primitif (stats, charts) — aman untuk di-cache.
     */
    private function buildAggregates(Carbon $today, Carbon $now): array
    {
        $stats = [
            'total_patients'    => Patient::count(),
            'total_doctors'     => Doctor::where('status', 'active')->count(),
            'appointments_today'=> Appointment::whereDate('appointment_date', $today)->count(),
            'appointments_month'=> Appointment::whereMonth('appointment_date', $now->month)
                ->whereYear('appointment_date', $now->year)->count(),
            'revenue_month'     => Payment::where('status', 'completed')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->sum('amount'),
            'total_users'       => User::count(),
            'total_drugs'       => Drug::count(),
            'total_rooms'       => Room::count(),
            'available_rooms'   => Room::where('status', 'available')->count(),
            'emergency_today'   => Emergency::whereDate('created_at', $today)->count(),
            'queue_active'      => Queue::where('status', 'waiting')->count(),
            'ambulance_active'  => Ambulance::where('status', 'available')->count(),
            // TODO: ganti jadi whereColumn('stock','<','min_stock') kalau kolom min_stock sudah ada
            'critical_drugs'    => Drug::where('stock', '<', 50)->count(),
            'occupied_rooms'    => Room::where('status', 'occupied')->count(),
            'patients_today'    => Patient::whereDate('created_at', $today)->count(),
        ];

        $totalRooms    = $stats['total_rooms'];
        $occupiedRooms = $stats['occupied_rooms'];
        $occupancyPct  = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

        $patientChart   = $this->getPatientChart();
        $revenueChart   = $this->getRevenueChart();
        $polyclinicChart= $this->getPolyclinicChart();
        $hourlyChart    = $this->getHourlyChart($today);
        $roomOccupancy  = [
            'labels' => ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'ICU', 'NICU', 'OK'],
            'data'   => $this->getOccupancyBreakdown(),
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
        $recentPayments     = Payment::with(['appointment.patient'])->latest()->take(5)->get();
        $recentPatients     = Patient::latest()->take(5)->get();
        $liveQueues         = Queue::with(['patient', 'polyclinic', 'doctor'])
            ->where('status', 'waiting')
            ->orderBy('queue_number')->take(8)->get();
        $ambulanceCalls     = AmbulanceCall::with('ambulance')
            ->whereDate('created_at', $today)->latest()->take(5)->get();

        $totalRooms    = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $roomAlerts    = Room::whereIn('status', ['occupied', 'maintenance'])
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
        $start  = now()->subDays(6)->startOfDay();
        $raw    = Patient::selectRaw("DATE(created_at) as d, COUNT(*) as c")
            ->where('created_at', '>=', $start)
            ->groupBy('d')->orderBy('d')
            ->pluck('c', 'd');

        $labels = [];
        $data   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date   = now()->subDays($i)->startOfDay();
            $key    = $date->toDateString();
            $labels[] = $date->translatedFormat('D');
            $data[]   = $raw[$key] ?? 0;
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
        $data   = [];
        for ($i = 5; $i >= 0; $i--) {
            $month  = now()->subMonths($i);
            $key    = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M');
            $data[]   = (int) ($raw[$key] ?? 0);
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
            'data'   => $clinics->pluck('total')->toArray(),
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
        $hourQ   = $isSqlite ? "CAST(strftime('%H', created_at) AS INTEGER)"    : 'HOUR(created_at)';

        $appRaw = Appointment::whereDate('appointment_date', $today)
            ->selectRaw("{$hourApp} as h, COUNT(*) as c")
            ->groupBy('h')->pluck('c', 'h');

        $queueRaw = Queue::whereDate('created_at', $today)
            ->selectRaw("{$hourQ} as h, COUNT(*) as c")
            ->groupBy('h')->pluck('c', 'h');

        $labels = [];
        $data   = [];
        for ($h = 0; $h < 24; $h++) {
            $labels[] = sprintf('%02d:00', $h);
            $data[]   = ($appRaw[$h] ?? 0) + ($queueRaw[$h] ?? 0);
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
            ->mapWithKeys(fn($r) => [$r->category => $r->total > 0 ? round(($r->occupied / $r->total) * 100) : 0])
            ->toArray();
    }
}

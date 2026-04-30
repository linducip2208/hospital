<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::where('status', 'active')->count(),
            'appointments_today' => Appointment::whereDate('appointment_date', today())->count(),
            'appointments_month' => Appointment::whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)->count(),
            'revenue_month' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['appointment.patient'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentAppointments', 'recentPayments'));
    }
}
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $patient = Auth::guard('patient')->user();

        $stats = [
            'appointments' => Appointment::where('patient_id', $patient->id)->count(),
            'upcoming' => Appointment::where('patient_id', $patient->id)
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->whereDate('appointment_date', '>=', now())->count(),
            'records' => MedicalRecord::where('patient_id', $patient->id)->count(),
            'unpaid' => Payment::where('patient_id', $patient->id)->where('status', 'pending')->sum('amount'),
        ];

        $upcomingAppointments = Appointment::with('doctor', 'polyclinic')
            ->where('patient_id', $patient->id)
            ->whereDate('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->take(5)->get();

        $recentPayments = Payment::where('patient_id', $patient->id)
            ->latest()->take(5)->get();

        return view('portal.dashboard', compact('patient', 'stats', 'upcomingAppointments', 'recentPayments'));
    }
}

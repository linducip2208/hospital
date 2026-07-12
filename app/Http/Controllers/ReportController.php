<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $total_patients = Patient::count();
        $total_doctors = Doctor::count();
        $total_appointments = Appointment::count();
        $total_payments = Payment::where('status', 'completed')->sum('amount');
        $total_drugs = Drug::count();
        $total_rooms = Room::count();

        $revenue_by_month = Payment::where('status', 'completed')
            ->selectRaw('SUM(amount) as total, MONTH(created_at) month, YEAR(created_at) year')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $appointments_by_status = Appointment::selectRaw('status, COUNT(*) count')
            ->groupBy('status')
            ->get();

        $top_treatments = Treatment::withCount('appointments')
            ->orderByDesc('appointments_count')
            ->take(5)
            ->get();

        $recent_payments = Payment::with('patient')
            ->latest()
            ->take(10)
            ->get();

        return view('reports.index', compact(
            'total_patients',
            'total_doctors',
            'total_appointments',
            'total_payments',
            'total_drugs',
            'total_rooms',
            'revenue_by_month',
            'appointments_by_status',
            'top_treatments',
            'recent_payments'
        ));
    }
}

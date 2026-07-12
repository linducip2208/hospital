<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $patient = Auth::guard('patient')->user();

        $appointments = Appointment::with('doctor', 'polyclinic', 'treatment')
            ->where('patient_id', $patient->id)
            ->orderByDesc('appointment_date')
            ->paginate(12);

        return view('portal.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment): View
    {
        $this->authorizeOwnership($appointment);
        $appointment->load('doctor', 'polyclinic', 'treatment', 'medicalRecord');

        return view('portal.appointments.show', compact('appointment'));
    }

    protected function authorizeOwnership(Appointment $appointment): void
    {
        abort_unless($appointment->patient_id === Auth::guard('patient')->id(), 403);
    }
}

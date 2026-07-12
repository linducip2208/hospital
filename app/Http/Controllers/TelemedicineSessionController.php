<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\TelemedicineSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TelemedicineSessionController extends Controller
{
    public function index(): View
    {
        $sessions = TelemedicineSession::with(['patient', 'doctor'])->latest()->paginate(15);
        return view('telemedicine-sessions.index', compact('sessions'));
    }

    public function create(): View
    {
        return view('telemedicine-sessions.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['session_no'] = sprintf('TM/%s/%04d', now()->format('Ymd'), TelemedicineSession::whereDate('created_at', today())->count() + 1);
        TelemedicineSession::create($validated);
        return redirect()->route('telemedicine-sessions.index')->with('success', 'Sesi tele dijadwalkan.');
    }

    public function show(TelemedicineSession $telemedicineSession): View
    {
        return view('telemedicine-sessions.show', ['session' => $telemedicineSession->load(['patient', 'doctor'])]);
    }

    public function edit(TelemedicineSession $telemedicineSession): View
    {
        return view('telemedicine-sessions.edit', [
            'session' => $telemedicineSession,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, TelemedicineSession $telemedicineSession): RedirectResponse
    {
        $telemedicineSession->update($this->validateRequest($request));
        return redirect()->route('telemedicine-sessions.show', $telemedicineSession)->with('success', 'Diperbarui.');
    }

    public function destroy(TelemedicineSession $telemedicineSession): RedirectResponse
    {
        $telemedicineSession->delete();
        return redirect()->route('telemedicine-sessions.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'scheduled_at' => 'required|date',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
            'platform' => 'nullable|string|max:100',
            'meeting_url' => 'nullable|url|max:500',
            'meeting_id' => 'nullable|string|max:100',
            'status' => 'nullable|in:scheduled,ongoing,completed,no_show,cancelled',
            'chief_complaint' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
        ]);
    }
}

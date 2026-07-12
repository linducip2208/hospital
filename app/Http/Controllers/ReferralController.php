<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Referral;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferralController extends Controller
{
    public function index(Request $request): View
    {
        $query = Referral::with(['fromPolyclinic', 'toPolyclinic', 'patient', 'doctor']);
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $referrals = $query->latest()->paginate(15);
        return view('referrals.index', compact('referrals'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();
        return view('referrals.create', compact('patients', 'doctors', 'polyclinics'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'from_polyclinic_id' => 'nullable|exists:polyclinics,id',
            'to_polyclinic_id' => 'required|exists:polyclinics,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'reason' => 'required|string',
            'diagnosis' => 'nullable|string',
        ]);
        Referral::create($validated);
        return redirect()->route('referrals.index')->with('success', 'Rujukan berhasil dibuat.');
    }

    public function show(Referral $referral): View
    {
        $referral->load(['fromPolyclinic', 'toPolyclinic', 'patient', 'doctor']);
        return view('referrals.show', compact('referral'));
    }

    public function edit(Referral $referral): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();
        return view('referrals.edit', compact('referral', 'patients', 'doctors', 'polyclinics'));
    }

    public function update(Request $request, Referral $referral): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'from_polyclinic_id' => 'nullable|exists:polyclinics,id',
            'to_polyclinic_id' => 'required|exists:polyclinics,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'reason' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $referral->update($validated);
        return redirect()->route('referrals.index')->with('success', 'Rujukan berhasil diperbarui.');
    }

    public function destroy(Referral $referral): RedirectResponse
    {
        $referral->delete();
        return redirect()->route('referrals.index')->with('success', 'Rujukan berhasil dihapus.');
    }

    public function print(Referral $referral): View
    {
        $referral->load(['fromPolyclinic', 'toPolyclinic', 'patient', 'doctor']);
        return view('referrals.print', compact('referral'));
    }
}

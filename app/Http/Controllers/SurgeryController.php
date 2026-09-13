<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\Surgery;
use App\Services\BillingService;
use App\Services\EncounterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurgeryController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensurePermission('encounters.manage');
        $query = Surgery::with(['patient', 'doctor', 'encounter']);
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($scheduled = $request->get('scheduled_date')) {
            $query->whereDate('scheduled_date', $scheduled);
        }
        $surgeries = $query->latest()->paginate(15);
        return view('surgeries.index', compact('surgeries'));
    }

    public function create(): View
    {
        $this->ensurePermission('encounters.manage');
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('surgeries.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            'encounter_id' => 'nullable|exists:encounters,id', 'surgeon_id' => 'nullable|exists:doctors,id', 'assistant_id' => 'nullable|exists:doctors,id', 'anesthetist_id' => 'nullable|exists:doctors,id',
            'operating_room' => 'nullable|string|max:100', 'anesthesia' => 'nullable|string|max:100', 'implants_materials' => 'nullable|string', 'outcome' => 'nullable|string|max:255', 'complications' => 'nullable|string', 'charge_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $surgery = DB::transaction(function () use ($validated) {
            $patient = Patient::findOrFail($validated['patient_id']);
            if (! empty($validated['encounter_id'])) abort_unless(Encounter::whereKey($validated['encounter_id'])->where('patient_id', $patient->id)->exists(), 422, 'Encounter operasi bukan milik pasien ini.');
            $validated['encounter_id'] ??= app(EncounterService::class)->forPatient($patient, $validated['doctor_id'] ?? null, auth()->id(), 'surgery')->id;
            $validated['surgeon_id'] ??= $validated['doctor_id'] ?? null;
            return Surgery::create($validated);
        });
        return redirect()->route('surgeries.show', $surgery)->with('success', 'Operasi dicatat dan surgical encounter dibuat.');
    }

    public function show(Surgery $surgery): View
    {
        $this->ensurePermission('encounters.manage');
        $surgery->load(['patient', 'doctor', 'encounter']);
        return view('surgeries.show', compact('surgery'));
    }

    public function edit(Surgery $surgery): View
    {
        $this->ensurePermission('encounters.manage');
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('surgeries.edit', compact('surgery', 'patients', 'doctors'));
    }

    public function update(Request $request, Surgery $surgery): RedirectResponse
    {
        $this->ensurePermission('encounters.manage');
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            'encounter_id' => 'nullable|exists:encounters,id', 'surgeon_id' => 'nullable|exists:doctors,id', 'assistant_id' => 'nullable|exists:doctors,id', 'anesthetist_id' => 'nullable|exists:doctors,id',
            'operating_room' => 'nullable|string|max:100', 'anesthesia' => 'nullable|string|max:100', 'implants_materials' => 'nullable|string', 'outcome' => 'nullable|string|max:255', 'complications' => 'nullable|string', 'charge_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        if (! empty($validated['encounter_id'])) abort_unless(Encounter::whereKey($validated['encounter_id'])->where('patient_id', $surgery->patient_id)->exists(), 422, 'Encounter operasi bukan milik pasien ini.');
        DB::transaction(function () use ($surgery, $validated) {
            $wasCompleted = $surgery->status === 'completed';
            $surgery->update($validated + (($validated['status'] ?? null) === 'in_progress' && ! $surgery->started_at ? ['started_at' => now()] : []) + (($validated['status'] ?? null) === 'completed' && ! $surgery->ended_at ? ['ended_at' => now()] : []));
            if (($validated['status'] ?? null) === 'completed' && ! $wasCompleted && (float) $surgery->charge_amount > 0 && $surgery->encounter_id) {
                app(BillingService::class)->addCharge(['patient_id' => $surgery->patient_id, 'encounter_id' => $surgery->encounter_id, 'source_type' => 'surgery', 'source_id' => $surgery->id, 'description' => $surgery->name, 'quantity' => 1, 'unit_price' => $surgery->charge_amount, 'amount' => $surgery->charge_amount]);
            }
        });
        return redirect()->route('surgeries.index')->with('success', 'Operasi berhasil diperbarui.');
    }

    public function destroy(Surgery $surgery): RedirectResponse
    {
        $this->ensurePermission('encounters.manage');
        $surgery->delete();
        return redirect()->route('surgeries.index')->with('success', 'Operasi berhasil dihapus.');
    }

    public function print(Surgery $surgery): View
    {
        $this->ensurePermission('encounters.manage');
        $surgery->load(['patient', 'doctor']);
        return view('surgeries.print', compact('surgery'));
    }
}

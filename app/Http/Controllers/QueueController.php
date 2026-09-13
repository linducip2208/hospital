<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Queue;
use App\Services\DocumentNumberService;
use App\Services\EncounterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(Request $request): View
    {
        $query = Queue::with(['polyclinic', 'patient', 'doctor']);

        if ($polyclinicId = $request->get('polyclinic_id')) {
            $query->where('polyclinic_id', $polyclinicId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($date = $request->get('date')) {
            $query->whereDate('created_at', $date);
        }

        $queues = $query->latest()->paginate(15);
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();

        return view('queues.index', compact('queues', 'polyclinics'));
    }

    public function create(): View
    {
        $polyclinics = Polyclinic::where('is_active', true)->get();
        $patients = Patient::where('is_active', true)->get();
        $doctors = Doctor::where('status', 'active')->get();

        return view('queues.create', compact('polyclinics', 'patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'notes' => 'nullable|string',
        ]);

        $polyclinic = Polyclinic::findOrFail($validated['polyclinic_id']);
        if (! empty($validated['appointment_id'])) {
            $appointment = \App\Models\Appointment::findOrFail($validated['appointment_id']);
            abort_unless($appointment->patient_id === (int) $validated['patient_id'], 422, 'Appointment bukan milik pasien ini.');
            if ($appointment->polyclinic_id && $appointment->polyclinic_id !== (int) $validated['polyclinic_id']) {
                abort(422, 'Poli antrian harus sama dengan poli appointment.');
            }
        }

        DB::transaction(function () use (&$validated, $polyclinic) {
            $validated['queue_number'] = app(DocumentNumberService::class)->next('queue:'.$polyclinic->id.':'.today()->format('Ymd'), $polyclinic->code, 3);
            $validated['status'] = 'waiting';
            $queue = Queue::create($validated);
            app(EncounterService::class)->fromQueue($queue, auth()->id());
        });

        return redirect()->route('queues.index')->with('success', 'Antrian berhasil ditambahkan.');
    }

    public function show(Queue $queue): View
    {
        $queue->load(['polyclinic', 'patient', 'doctor']);

        return view('queues.show', compact('queue'));
    }

    public function edit(Queue $queue): View
    {
        $polyclinics = Polyclinic::where('is_active', true)->get();
        $patients = Patient::where('is_active', true)->get();
        $doctors = Doctor::where('status', 'active')->get();

        return view('queues.edit', compact('queue', 'polyclinics', 'patients', 'doctors'));
    }

    public function update(Request $request, Queue $queue): RedirectResponse
    {
        $validated = $request->validate([
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'status' => 'required|in:waiting,called,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $queue->update($validated);

        return redirect()->route('queues.index')->with('success', 'Antrian berhasil diperbarui.');
    }

    public function destroy(Queue $queue): RedirectResponse
    {
        $queue->delete();

        return redirect()->route('queues.index')->with('success', 'Antrian berhasil dihapus.');
    }

    public function call(Queue $queue): RedirectResponse
    {
        $encounter = app(EncounterService::class)->fromQueue($queue, auth()->id());
        app(EncounterService::class)->start($encounter);
        $queue->update([
            'status' => 'called',
            'called_at' => now(),
        ]);

        return back()->with('success', 'Antrian ' . $queue->queue_number . ' telah dipanggil.');
    }

    public function complete(Queue $queue): RedirectResponse
    {
        $queue->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        if ($queue->encounter) {
            app(EncounterService::class)->complete($queue->encounter);
        }

        return back()->with('success', 'Antrian ' . $queue->queue_number . ' telah selesai.');
    }
}

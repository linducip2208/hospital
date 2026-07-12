<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Queue;
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
            'notes' => 'nullable|string',
        ]);

        $polyclinic = Polyclinic::findOrFail($validated['polyclinic_id']);

        $todayCount = Queue::where('polyclinic_id', $validated['polyclinic_id'])
            ->whereDate('created_at', today())
            ->count();

        $validated['queue_number'] = $polyclinic->code . '-' . str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);
        $validated['status'] = 'waiting';

        Queue::create($validated);

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

        return back()->with('success', 'Antrian ' . $queue->queue_number . ' telah selesai.');
    }
}

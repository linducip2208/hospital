<?php

namespace App\Http\Controllers;

use App\Models\HospitalBed;
use App\Models\Patient;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HospitalBedController extends Controller
{
    public function index(Request $request): View
    {
        $query = HospitalBed::with(['room', 'currentPatient']);
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($roomId = $request->get('room_id')) {
            $query->where('room_id', $roomId);
        }
        $beds = $query->orderBy('room_id')->orderBy('bed_code')->paginate(30)->withQueryString();
        return view('hospital-beds.index', [
            'beds' => $beds,
            'rooms' => Room::orderBy('room_number')->get(),
            'statuses' => HospitalBed::STATUSES,
        ]);
    }

    public function create(): View
    {
        return view('hospital-beds.create', [
            'rooms' => Room::orderBy('room_number')->get(),
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'statuses' => HospitalBed::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        HospitalBed::create($this->validateRequest($request));
        return redirect()->route('hospital-beds.index')->with('success', 'Bed dibuat.');
    }

    public function edit(HospitalBed $hospitalBed): View
    {
        return view('hospital-beds.edit', [
            'bed' => $hospitalBed,
            'rooms' => Room::orderBy('room_number')->get(),
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'statuses' => HospitalBed::STATUSES,
        ]);
    }

    public function update(Request $request, HospitalBed $hospitalBed): RedirectResponse
    {
        $hospitalBed->update($this->validateRequest($request));
        return redirect()->route('hospital-beds.index')->with('success', 'Bed diperbarui.');
    }

    public function destroy(HospitalBed $hospitalBed): RedirectResponse
    {
        $hospitalBed->delete();
        return redirect()->route('hospital-beds.index')->with('success', 'Bed dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'bed_code' => 'required|string|max:50',
            'label' => 'nullable|string|max:100',
            'status' => 'required|in:' . implode(',', array_keys(HospitalBed::STATUSES)),
            'current_patient_id' => 'nullable|exists:patients,id',
            'occupied_since' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
    }
}

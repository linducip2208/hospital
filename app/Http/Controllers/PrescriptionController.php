<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\Prescription;
use App\Services\DocumentNumberService;
use App\Services\PharmacyDispensingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensurePermission('prescriptions.create');
        $query = Prescription::with(['patient', 'doctor', 'items']);
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $prescriptions = $query->latest()->paginate(15)->withQueryString();
        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create(): View
    {
        $this->ensurePermission('prescriptions.create');
        return view('prescriptions.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'drugs' => Drug::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensurePermission('prescriptions.create');
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'encounter_id' => 'nullable|exists:encounters,id',
            'prescribed_at' => 'required|date',
            'is_iter' => 'nullable|boolean',
            'iter_count' => 'nullable|integer|min:0|max:10',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_id' => 'nullable|exists:drugs,id',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.dose' => 'nullable|string|max:100',
            'items.*.frequency' => 'nullable|string|max:100',
            'items.*.route' => 'nullable|string|max:50',
            'items.*.duration' => 'nullable|string|max:100',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.instructions' => 'nullable|string',
            'items.*.is_compounded' => 'nullable|boolean',
            'items.*.is_high_alert' => 'nullable|boolean',
        ]);

        $prescription = DB::transaction(function () use ($validated) {
            $items = $validated['items'];
            unset($validated['items']);
            $validated['rx_no'] = app(DocumentNumberService::class)->next('prescription', 'RX', 4);
            $validated['status'] = 'issued';
            $rx = Prescription::create($validated);
            foreach ($items as $item) {
                $rx->items()->create($item);
            }
            return $rx;
        });

        return redirect()->route('prescriptions.show', $prescription)->with('success', 'Resep dibuat.');
    }

    public function show(Prescription $prescription): View
    {
        $this->ensurePermission('prescriptions.create');
        $prescription->load(['patient', 'doctor', 'items.drug']);
        return view('prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription): View
    {
        $this->ensurePermission('prescriptions.create');
        $prescription->load('items');
        return view('prescriptions.edit', [
            'prescription' => $prescription,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'drugs' => Drug::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Prescription $prescription): RedirectResponse
    {
        $this->ensurePermission('prescriptions.create');
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'encounter_id' => 'nullable|exists:encounters,id',
            'prescribed_at' => 'required|date',
            'is_iter' => 'nullable|boolean',
            'iter_count' => 'nullable|integer|min:0|max:10',
            'status' => 'required|in:draft,issued,dispensed,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_id' => 'nullable|exists:drugs,id',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.dose' => 'nullable|string|max:100',
            'items.*.frequency' => 'nullable|string|max:100',
            'items.*.route' => 'nullable|string|max:50',
            'items.*.duration' => 'nullable|string|max:100',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.instructions' => 'nullable|string',
            'items.*.is_compounded' => 'nullable|boolean',
            'items.*.is_high_alert' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $prescription) {
            $items = $validated['items'];
            unset($validated['items']);
            $prescription->update($validated);
            $prescription->items()->delete();
            foreach ($items as $item) {
                $prescription->items()->create($item);
            }
        });

        return redirect()->route('prescriptions.show', $prescription)->with('success', 'Resep diperbarui.');
    }

    public function destroy(Prescription $prescription): RedirectResponse
    {
        $this->ensurePermission('prescriptions.create');
        $prescription->delete();
        return redirect()->route('prescriptions.index')->with('success', 'Resep dihapus.');
    }

    public function dispense(Prescription $prescription, PharmacyDispensingService $service): RedirectResponse
    {
        $service->dispense($prescription);

        return redirect()->route('prescriptions.show', $prescription)->with('success', 'Obat berhasil diverifikasi dan diserahkan dengan metode FEFO.');
    }

    public function print(Prescription $prescription): View
    {
        $prescription->load(['patient', 'doctor', 'items.drug']);
        return view('prescriptions.print', compact('prescription'));
    }

    public function printLabels(Prescription $prescription): View
    {
        $prescription->load(['patient', 'doctor', 'items.drug']);
        return view('prescriptions.print-labels', compact('prescription'));
    }

}

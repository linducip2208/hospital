<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\EquipmentCalibration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentCalibrationController extends Controller
{
    public function index(Request $request): View
    {
        $query = EquipmentCalibration::with('asset');

        if ($filter = $request->get('filter')) {
            if ($filter === 'overdue') {
                $query->where('next_due_date', '<', now())->where('status', '!=', 'completed');
            } elseif ($filter === 'due_soon') {
                $query->whereBetween('next_due_date', [now(), now()->addDays(30)]);
            }
        }

        $calibrations = $query->orderBy('next_due_date')->paginate(15);

        $stats = [
            'overdue' => EquipmentCalibration::where('next_due_date', '<', now())->where('status', '!=', 'completed')->count(),
            'due_soon' => EquipmentCalibration::whereBetween('next_due_date', [now(), now()->addDays(30)])->count(),
            'total' => EquipmentCalibration::count(),
        ];

        return view('equipment-calibrations.index', compact('calibrations', 'stats'));
    }

    public function create(): View
    {
        $assets = Asset::where('category', 'medical')->orderBy('name')->get();

        return view('equipment-calibrations.create', compact('assets'));
    }

    public function store(Request $request): RedirectResponse
    {
        EquipmentCalibration::create($this->validated($request));

        return redirect()->route('equipment-calibrations.index')->with('success', 'Data kalibrasi berhasil disimpan.');
    }

    public function edit(EquipmentCalibration $equipmentCalibration): View
    {
        $assets = Asset::where('category', 'medical')->orderBy('name')->get();

        return view('equipment-calibrations.edit', ['calibration' => $equipmentCalibration, 'assets' => $assets]);
    }

    public function update(Request $request, EquipmentCalibration $equipmentCalibration): RedirectResponse
    {
        $equipmentCalibration->update($this->validated($request));

        return redirect()->route('equipment-calibrations.index')->with('success', 'Data kalibrasi berhasil diperbarui.');
    }

    public function destroy(EquipmentCalibration $equipmentCalibration): RedirectResponse
    {
        $equipmentCalibration->delete();

        return redirect()->route('equipment-calibrations.index')->with('success', 'Data kalibrasi berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'calibration_date' => 'required|date',
            'next_due_date' => 'required|date|after:calibration_date',
            'performed_by' => 'nullable|string|max:255',
            'certificate_no' => 'nullable|string|max:255',
            'result' => 'required|in:pass,pass_with_note,fail',
            'status' => 'required|in:scheduled,completed,overdue',
            'notes' => 'nullable|string',
        ]);
    }
}

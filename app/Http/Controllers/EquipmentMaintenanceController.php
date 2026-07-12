<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\EquipmentMaintenance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentMaintenanceController extends Controller
{
    public function index(): View
    {
        $maintenances = EquipmentMaintenance::with('asset')->latest('scheduled_date')->paginate(15);
        return view('equipment-maintenances.index', compact('maintenances'));
    }

    public function create(): View
    {
        return view('equipment-maintenances.create', [
            'assets' => Asset::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        EquipmentMaintenance::create($this->validateRequest($request));
        return redirect()->route('equipment-maintenances.index')->with('success', 'Jadwal dibuat.');
    }

    public function show(EquipmentMaintenance $equipmentMaintenance): View
    {
        return view('equipment-maintenances.show', ['maintenance' => $equipmentMaintenance->load('asset')]);
    }

    public function edit(EquipmentMaintenance $equipmentMaintenance): View
    {
        return view('equipment-maintenances.edit', [
            'maintenance' => $equipmentMaintenance,
            'assets' => Asset::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, EquipmentMaintenance $equipmentMaintenance): RedirectResponse
    {
        $equipmentMaintenance->update($this->validateRequest($request));
        return redirect()->route('equipment-maintenances.show', $equipmentMaintenance)->with('success', 'Diperbarui.');
    }

    public function destroy(EquipmentMaintenance $equipmentMaintenance): RedirectResponse
    {
        $equipmentMaintenance->delete();
        return redirect()->route('equipment-maintenances.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'scheduled_date' => 'required|date',
            'performed_date' => 'nullable|date',
            'maintenance_type' => 'required|in:preventive,corrective,calibration,inspection',
            'performer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'findings' => 'nullable|string',
            'action' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'result' => 'nullable|in:ok,needs_repair,replaced,failed',
            'next_due_date' => 'nullable|date',
            'status' => 'nullable|in:scheduled,in_progress,done,cancelled',
        ]);
    }
}

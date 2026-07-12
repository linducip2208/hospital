<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\MedicalWaste;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicalWasteController extends Controller
{
    public function index(Request $request): View
    {
        $query = MedicalWaste::with('department', 'vendor');

        if ($type = $request->get('waste_type')) {
            $query->where('waste_type', $type);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $wastes = $query->latest('collection_date')->paginate(15);

        $stats = [
            'total_kg_month' => (float) MedicalWaste::whereMonth('collection_date', now()->month)->sum('weight_kg'),
            'stored' => MedicalWaste::where('status', 'stored')->count(),
            'disposed_month' => MedicalWaste::where('status', 'disposed')->whereMonth('disposal_date', now()->month)->count(),
        ];

        $typeLabels = MedicalWaste::typeLabels();

        return view('medical-wastes.index', compact('wastes', 'stats', 'typeLabels'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $typeLabels = MedicalWaste::typeLabels();

        return view('medical-wastes.create', compact('departments', 'vendors', 'typeLabels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['manifest_no'] = 'WM-'.now()->format('Ymd').'-'.str_pad((string) (MedicalWaste::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT);

        MedicalWaste::create($data);

        return redirect()->route('medical-wastes.index')->with('success', 'Manifest limbah medis berhasil dibuat.');
    }

    public function edit(MedicalWaste $medicalWaste): View
    {
        $departments = Department::orderBy('name')->get();
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $typeLabels = MedicalWaste::typeLabels();

        return view('medical-wastes.edit', ['waste' => $medicalWaste, 'departments' => $departments, 'vendors' => $vendors, 'typeLabels' => $typeLabels]);
    }

    public function update(Request $request, MedicalWaste $medicalWaste): RedirectResponse
    {
        $medicalWaste->update($this->validated($request));

        return redirect()->route('medical-wastes.index')->with('success', 'Data limbah medis berhasil diperbarui.');
    }

    public function destroy(MedicalWaste $medicalWaste): RedirectResponse
    {
        $medicalWaste->delete();

        return redirect()->route('medical-wastes.index')->with('success', 'Data limbah medis berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'waste_type' => 'required|in:infectious,sharps,pharmaceutical,chemical,radioactive,pathological,general',
            'weight_kg' => 'required|numeric|min:0',
            'department_id' => 'nullable|exists:departments,id',
            'collection_date' => 'required|date',
            'disposal_date' => 'nullable|date',
            'transporter' => 'nullable|string|max:255',
            'vendor_id' => 'nullable|exists:vendors,id',
            'status' => 'required|in:stored,transported,disposed',
            'handled_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
    }
}

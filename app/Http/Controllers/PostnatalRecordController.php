<?php

namespace App\Http\Controllers;

use App\Models\Maternity;
use App\Models\Patient;
use App\Models\PostnatalRecord;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostnatalRecordController extends Controller
{
    public function index(Request $request): View
    {
        $query = PostnatalRecord::with(['patient', 'midwife', 'maternity']);
        if ($patientId = $request->get('patient_id')) {
            $query->where('patient_id', $patientId);
        }
        if ($visitDate = $request->get('visit_date')) {
            $query->whereDate('visit_date', $visitDate);
        }
        $postnatalRecords = $query->latest('visit_date')->paginate(15);
        return view('postnatal-records.index', compact('postnatalRecords'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->where('gender', 'female')->orderBy('name')->get();
        $midwives = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        $maternities = Maternity::with('patient')->latest()->get();
        return view('postnatal-records.create', compact('patients', 'midwives', 'maternities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'midwife_id' => 'required|exists:users,id',
            'maternity_id' => 'nullable|exists:maternities,id',
            'visit_date' => 'required|date',
            'uterine_involution' => 'nullable|string|max:255',
            'lochia' => 'nullable|string|max:255',
            'perineum_wound' => 'nullable|string|max:255',
            'breastfeeding' => 'nullable|string|max:255',
            'baby_weight' => 'nullable|numeric|min:0|max:10',
            'baby_condition' => 'nullable|string|max:255',
            'complications' => 'nullable|string',
            'family_planning' => 'nullable|string|max:255',
            'next_visit_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        PostnatalRecord::create($validated);
        return redirect()->route('postnatal-records.index')->with('success', 'Data postnatal berhasil dicatat.');
    }

    public function show(PostnatalRecord $postnatalRecord): View
    {
        $postnatalRecord->load(['patient', 'midwife', 'maternity']);
        return view('postnatal-records.show', compact('postnatalRecord'));
    }

    public function edit(PostnatalRecord $postnatalRecord): View
    {
        $patients = Patient::where('is_active', true)->where('gender', 'female')->orderBy('name')->get();
        $midwives = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        $maternities = Maternity::with('patient')->latest()->get();
        return view('postnatal-records.edit', compact('postnatalRecord', 'patients', 'midwives', 'maternities'));
    }

    public function update(Request $request, PostnatalRecord $postnatalRecord): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'midwife_id' => 'required|exists:users,id',
            'maternity_id' => 'nullable|exists:maternities,id',
            'visit_date' => 'required|date',
            'uterine_involution' => 'nullable|string|max:255',
            'lochia' => 'nullable|string|max:255',
            'perineum_wound' => 'nullable|string|max:255',
            'breastfeeding' => 'nullable|string|max:255',
            'baby_weight' => 'nullable|numeric|min:0|max:10',
            'baby_condition' => 'nullable|string|max:255',
            'complications' => 'nullable|string',
            'family_planning' => 'nullable|string|max:255',
            'next_visit_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $postnatalRecord->update($validated);
        return redirect()->route('postnatal-records.index')->with('success', 'Data postnatal berhasil diperbarui.');
    }

    public function destroy(PostnatalRecord $postnatalRecord): RedirectResponse
    {
        $postnatalRecord->delete();
        return redirect()->route('postnatal-records.index')->with('success', 'Data postnatal berhasil dihapus.');
    }
}

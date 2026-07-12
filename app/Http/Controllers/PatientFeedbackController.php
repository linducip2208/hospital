<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientFeedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientFeedbackController extends Controller
{
    public function index(): View
    {
        $feedbacks = PatientFeedback::with('patient')->latest()->paginate(15);
        $stats = [
            'avg_overall' => PatientFeedback::avg('rating_overall'),
            'avg_doctor' => PatientFeedback::avg('rating_doctor'),
            'avg_nurse' => PatientFeedback::avg('rating_nurse'),
            'count' => PatientFeedback::count(),
            'recommend_pct' => PatientFeedback::whereNotNull('would_recommend')->count() > 0
                ? round(PatientFeedback::where('would_recommend', true)->count() * 100 / max(PatientFeedback::whereNotNull('would_recommend')->count(), 1), 1)
                : 0,
        ];
        return view('patient-feedbacks.index', compact('feedbacks', 'stats'));
    }

    public function create(): View
    {
        return view('patient-feedbacks.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        PatientFeedback::create($this->validateRequest($request));
        return redirect()->route('patient-feedbacks.index')->with('success', 'Feedback dicatat. Terima kasih!');
    }

    public function show(PatientFeedback $patientFeedback): View
    {
        return view('patient-feedbacks.show', ['feedback' => $patientFeedback->load('patient')]);
    }

    public function edit(PatientFeedback $patientFeedback): View
    {
        return view('patient-feedbacks.edit', [
            'feedback' => $patientFeedback,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, PatientFeedback $patientFeedback): RedirectResponse
    {
        $patientFeedback->update($this->validateRequest($request));
        return redirect()->route('patient-feedbacks.show', $patientFeedback)->with('success', 'Diperbarui.');
    }

    public function destroy(PatientFeedback $patientFeedback): RedirectResponse
    {
        $patientFeedback->delete();
        return redirect()->route('patient-feedbacks.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'visit_date' => 'nullable|date',
            'service_type' => 'nullable|string|max:100',
            'rating_overall' => 'nullable|integer|min:1|max:5',
            'rating_doctor' => 'nullable|integer|min:1|max:5',
            'rating_nurse' => 'nullable|integer|min:1|max:5',
            'rating_facility' => 'nullable|integer|min:1|max:5',
            'rating_cleanliness' => 'nullable|integer|min:1|max:5',
            'rating_speed' => 'nullable|integer|min:1|max:5',
            'would_recommend' => 'nullable|boolean',
            'positive' => 'nullable|string',
            'negative' => 'nullable|string',
            'suggestion' => 'nullable|string',
            'is_anonymous' => 'nullable|boolean',
            'respondent_name' => 'nullable|string|max:255',
            'respondent_contact' => 'nullable|string|max:100',
            'status' => 'nullable|in:new,reviewed,responded,closed',
        ]);
    }
}

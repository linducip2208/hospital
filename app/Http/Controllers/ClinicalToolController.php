<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\Clinical\DrugInteractionChecker;
use App\Services\Clinical\Icd10Service;
use App\Services\Clinical\InsuranceEligibilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClinicalToolController extends Controller
{
    /** GET /clinical/icd10?q=... */
    public function icd10(Request $request, Icd10Service $icd): JsonResponse
    {
        $results = $icd->search($request->get('q', ''));
        $out = [];
        foreach ($results as $code => $name) {
            $out[] = ['code' => $code, 'name' => $name];
        }

        return response()->json($out);
    }

    /** POST /clinical/drug-interactions {drugs:[], patient_id?} */
    public function drugInteractions(Request $request, DrugInteractionChecker $checker): JsonResponse
    {
        $drugs = (array) $request->input('drugs', []);
        $patient = $request->filled('patient_id') ? Patient::find($request->input('patient_id')) : null;

        return response()->json($checker->check($drugs, $patient));
    }

    /** GET /clinical/eligibility/{patient} */
    public function eligibility(Patient $patient, InsuranceEligibilityService $svc): JsonResponse
    {
        return response()->json($svc->check($patient));
    }
}

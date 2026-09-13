<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Encounter;
use App\Models\HospitalBed;
use App\Services\AdmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function store(Request $request, AdmissionService $service): RedirectResponse
    {
        $data = $request->validate(['encounter_id' => 'required|exists:encounters,id', 'hospital_bed_id' => 'required|exists:hospital_beds,id']);
        $service->admit(Encounter::findOrFail($data['encounter_id']), HospitalBed::findOrFail($data['hospital_bed_id']));

        return back()->with('success', 'Pasien berhasil ditempatkan di bed.');
    }

    public function discharge(Request $request, Admission $admission, AdmissionService $service): RedirectResponse
    {
        $override = $request->boolean('override');
        $service->discharge($admission, $override);

        return back()->with('success', $override ? 'Pasien discharge dengan override ber-audit.' : 'Pasien berhasil discharge.');
    }
}

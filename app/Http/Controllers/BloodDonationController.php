<?php

namespace App\Http\Controllers;

use App\Models\BloodDonation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BloodDonationController extends Controller
{
    public function index(Request $request): View
    {
        $query = BloodDonation::query();
        if ($bloodType = $request->get('blood_type')) {
            $query->where('blood_type', $bloodType);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $donations = $query->latest()->paginate(15);
        $bloodTypes = BloodDonation::select('blood_type')->distinct()->pluck('blood_type');
        return view('blood-donations.index', compact('donations', 'bloodTypes'));
    }

    public function create(): View
    {
        return view('blood-donations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'blood_type' => 'required|string|max:5',
            'donation_date' => 'required|date',
            'quantity_ml' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $validated['expiry_date'] = \Carbon\Carbon::parse($validated['donation_date'])->addDays(42);
        BloodDonation::create($validated);
        return redirect()->route('blood-donations.index')->with('success', 'Donor darah berhasil dicatat.');
    }

    public function show(BloodDonation $bloodDonation): View
    {
        return view('blood-donations.show', compact('bloodDonation'));
    }

    public function edit(BloodDonation $bloodDonation): View
    {
        return view('blood-donations.edit', compact('bloodDonation'));
    }

    public function update(Request $request, BloodDonation $bloodDonation): RedirectResponse
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'blood_type' => 'required|string|max:5',
            'donation_date' => 'required|date',
            'quantity_ml' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $validated['expiry_date'] = \Carbon\Carbon::parse($validated['donation_date'])->addDays(42);
        $bloodDonation->update($validated);
        return redirect()->route('blood-donations.index')->with('success', 'Donor darah berhasil diperbarui.');
    }

    public function destroy(BloodDonation $bloodDonation): RedirectResponse
    {
        $bloodDonation->delete();
        return redirect()->route('blood-donations.index')->with('success', 'Donor darah berhasil dihapus.');
    }
}

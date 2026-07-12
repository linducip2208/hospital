<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreatmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Treatment::query();
        if ($search = $request->get('search')) {
            $query->whereAny(['name', 'category', 'description'], 'like', "%{$search}%");
        }
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }
        $treatments = $query->latest()->paginate(15);
        $categories = Treatment::select('category')->distinct()->whereNotNull('category')->pluck('category');
        return view('treatments.index', compact('treatments', 'categories'));
    }

    public function create(): View
    {
        return view('treatments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['requirements'] = $request->filled('requirements') ? explode("\n", str_replace("\r", "", $request->requirements)) : null;
        Treatment::create($validated);
        return redirect()->route('treatments.index')->with('success', 'Treatment berhasil ditambahkan.');
    }

    public function show(Treatment $treatment): View
    {
        $treatment->load('appointments.patient');
        return view('treatments.show', compact('treatment'));
    }

    public function edit(Treatment $treatment): View
    {
        return view('treatments.edit', compact('treatment'));
    }

    public function update(Request $request, Treatment $treatment): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['requirements'] = $request->filled('requirements') ? explode("\n", str_replace("\r", "", $request->requirements)) : null;
        $treatment->update($validated);
        return redirect()->route('treatments.index')->with('success', 'Treatment berhasil diperbarui.');
    }

    public function destroy(Treatment $treatment): RedirectResponse
    {
        $treatment->delete();
        return redirect()->route('treatments.index')->with('success', 'Treatment berhasil dihapus.');
    }
}

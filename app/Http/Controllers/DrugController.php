<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DrugController extends Controller
{
    public function index(Request $request): View
    {
        $query = Drug::query();
        if ($search = $request->get('search')) {
            $query->whereAny(['name', 'category'], 'like', "%{$search}%");
        }
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }
        $drugs = $query->latest()->paginate(15);
        $categories = Drug::select('category')->distinct()->whereNotNull('category')->pluck('category');
        return view('drugs.index', compact('drugs', 'categories'));
    }

    public function create(): View
    {
        return view('drugs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'stock' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        Drug::create($validated);
        return redirect()->route('drugs.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function show(Drug $drug): View
    {
        return view('drugs.show', compact('drug'));
    }

    public function edit(Drug $drug): View
    {
        return view('drugs.edit', compact('drug'));
    }

    public function update(Request $request, Drug $drug): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'stock' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $drug->update($validated);
        return redirect()->route('drugs.index')->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy(Drug $drug): RedirectResponse
    {
        $drug->delete();
        return redirect()->route('drugs.index')->with('success', 'Obat berhasil dihapus.');
    }
}

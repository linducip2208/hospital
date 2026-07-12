<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request): View
    {
        $query = Asset::with('department');
        if ($search = $request->get('search')) {
            $query->whereAny(['asset_code', 'name', 'supplier', 'serial_number'], 'like', "%{$search}%");
        }
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($departmentId = $request->get('department_id')) {
            $query->where('department_id', $departmentId);
        }
        $assets = $query->latest()->paginate(15);
        $categories = Asset::select('category')->distinct()->whereNotNull('category')->pluck('category');
        $departments = Department::orderBy('name')->get();
        return view('assets.index', compact('assets', 'categories', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('assets.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'asset_code' => 'nullable|string|max:50|unique:assets,asset_code',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'warranty_expiry' => 'nullable|date',
            'condition' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        Asset::create($validated);
        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Asset $asset): View
    {
        $asset->load('department');
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('assets.edit', compact('asset', 'departments'));
    }

    public function update(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'asset_code' => 'nullable|string|max:50|unique:assets,asset_code,' . $asset->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'warranty_expiry' => 'nullable|date',
            'condition' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $asset->update($validated);
        return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vendor::withCount('purchaseOrders');
        if ($search = $request->get('search')) {
            $query->whereAny(['name', 'code', 'contact_person'], 'like', "%{$search}%");
        }
        $vendors = $query->orderBy('name')->paginate(15);

        return view('vendors.index', compact('vendors'));
    }

    public function create(): View
    {
        return view('vendors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Vendor::create($this->validated($request));

        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function edit(Vendor $vendor): View
    {
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $vendor->update($this->validated($request, $vendor->id));

        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil diperbarui.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil dihapus.');
    }

    protected function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:vendors,code'.($id ? ",{$id}" : ''),
            'category' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}

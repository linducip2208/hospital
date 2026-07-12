<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $query = Room::query();
        if ($search = $request->get('search')) {
            $query->whereAny(['room_number', 'room_type'], 'like', "%{$search}%");
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $rooms = $query->latest()->paginate(15);
        return view('rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        return view('rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number',
            'room_type' => 'required|in:VIP,Kelas 1,Kelas 2,Kelas 3',
            'floor' => 'nullable|integer',
            'bed_count' => 'nullable|integer',
            'price_per_day' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|string',
            'status' => 'nullable|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);
        $validated['facilities'] = $request->filled('facilities') ? explode("\n", str_replace("\r", "", $request->facilities)) : null;
        Room::create($validated);
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(Room $room): View
    {
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room): View
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id,
            'room_type' => 'required|in:VIP,Kelas 1,Kelas 2,Kelas 3',
            'floor' => 'nullable|integer',
            'bed_count' => 'nullable|integer',
            'price_per_day' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|string',
            'status' => 'nullable|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);
        $validated['facilities'] = $request->filled('facilities') ? explode("\n", str_replace("\r", "", $request->facilities)) : null;
        $room->update($validated);
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}

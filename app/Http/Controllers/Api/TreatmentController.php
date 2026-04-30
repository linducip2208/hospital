<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Treatment::query();

        if ($search = $request->get('search')) {
            $query->whereAny(['name', 'category', 'description'], 'like', "%{$search}%");
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        return response()->json(
            $query->latest()->paginate($request->get('per_page', 15))
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'requirements' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $treatment = Treatment::create($validated);

        return response()->json($treatment, 201);
    }

    public function show(Treatment $treatment): JsonResponse
    {
        return response()->json($treatment);
    }

    public function update(Request $request, Treatment $treatment): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'requirements' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $treatment->update($validated);

        return response()->json($treatment);
    }

    public function destroy(Treatment $treatment): JsonResponse
    {
        $treatment->delete();
        return response()->json(null, 204);
    }
}

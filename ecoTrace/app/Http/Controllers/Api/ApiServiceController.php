<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Analytics;
use App\Http\Resources\ServiceResource;
use App\Rules\ValidEwasteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiServiceController extends Controller
{
    /**
     * Get paginated services list with advanced filtering.
     */
    public function index(Request $request)
    {
        $location = $request->query('location');
        $categoryId = $request->query('category_id');

        // Log search event in analytics
        Analytics::log('search', [
            'location' => $location,
            'category_id' => $categoryId,
            'source' => 'api',
        ]);

        $query = Service::with(['user', 'category'])->where('status', 'active');

        if (!empty($location)) {
            $query->where('location', 'like', '%' . $location . '%');
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        // Return paginated resources
        $services = $query->paginate(10);

        return ServiceResource::collection($services);
    }

    public function store(Request $request)
    {
        // Authenticate request simulated/real
        $user = auth()->user() ?? \App\Models\User::where('role', 'collector')->first();

        if (!$user || $user->role !== 'collector') {
            return response()->json(['message' => 'Unauthorized. Only collectors can create services.'], 403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'cost_per_kg' => 'required|numeric|min:0',
            'ewaste_types' => ['required', 'array', new ValidEwasteType],
        ]);

        $service = Service::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'cost_per_kg' => $request->cost_per_kg,
            'status' => 'active',
            'ewaste_types' => $request->ewaste_types,
        ]);

        // Clear filter caches
        Cache::flush();

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully!',
            'data' => new ServiceResource($service)
        ], 201);
    }

    public function show($id)
    {
        $service = Service::with(['user', 'category'])->findOrFail($id);
        return new ServiceResource($service);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'sometimes|required|string|max:255',
            'cost_per_kg' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:active,inactive',
            'ewaste_types' => ['sometimes', 'required', 'array', new ValidEwasteType],
        ]);

        $service->update($request->only([
            'category_id',
            'title',
            'description',
            'location',
            'cost_per_kg',
            'status',
            'ewaste_types',
        ]));

        Cache::flush();

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully!',
            'data' => new ServiceResource($service)
        ]);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        Cache::flush();

        return response()->json([
            'success' => true,
            'message' => 'Service soft deleted successfully!'
        ]);
    }
}

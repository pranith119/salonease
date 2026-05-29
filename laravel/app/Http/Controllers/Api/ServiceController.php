<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    /**
     * Display a paginated listing of services.
     * Requires scope: services:read
     * Uses response caching for performance.
     *
     * GET /api/services?search=...&per_page=...
     */
    public function index(Request $request): JsonResponse
    {
        if (! $request->user()->tokenCan('services:read')) {
            return response()->json(['message' => 'Insufficient scope: services:read required.'], 403);
        }

        $search = $request->input('search', '');
        $perPage = min((int) $request->input('per_page', 15), 100);
        $page = (int) $request->input('page', 1);

        // Cache the service list for 5 minutes (keyed by search/page/perPage)
        $cacheKey = "services:list:{$search}:{$page}:{$perPage}";

        $services = Cache::remember($cacheKey, 300, function () use ($search, $perPage) {
            $query = Service::query();

            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }

            return $query->orderBy('name')->paginate($perPage);
        });

        return response()->json($services);
    }

    /**
     * Store a newly created service.
     * Requires scope: services:write
     *
     * POST /api/services
     */
    public function store(Request $request): JsonResponse
    {
        if (! $request->user()->tokenCan('services:write')) {
            return response()->json(['message' => 'Insufficient scope: services:write required.'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:480'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
        ]);

        $service = Service::create($validated);

        // Clear service cache on write
        Cache::flush();

        return response()->json([
            'message' => 'Service created successfully.',
            'service' => $service,
        ], 201);
    }

    /**
     * Display the specified service.
     * Requires scope: services:read
     *
     * GET /api/services/{service}
     */
    public function show(Request $request, Service $service): JsonResponse
    {
        if (! $request->user()->tokenCan('services:read')) {
            return response()->json(['message' => 'Insufficient scope: services:read required.'], 403);
        }

        return response()->json(['service' => $service]);
    }

    /**
     * Update the specified service.
     * Requires scope: services:write
     *
     * PUT /api/services/{service}
     */
    public function update(Request $request, Service $service): JsonResponse
    {
        if (! $request->user()->tokenCan('services:write')) {
            return response()->json(['message' => 'Insufficient scope: services:write required.'], 403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'duration_minutes' => ['sometimes', 'required', 'integer', 'min:1', 'max:480'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999.99'],
        ]);

        $service->update($validated);

        // Clear service cache on write
        Cache::flush();

        return response()->json([
            'message' => 'Service updated successfully.',
            'service' => $service->fresh(),
        ]);
    }

    /**
     * Remove the specified service.
     * Requires scope: services:write
     *
     * DELETE /api/services/{service}
     */
    public function destroy(Request $request, Service $service): JsonResponse
    {
        if (! $request->user()->tokenCan('services:write')) {
            return response()->json(['message' => 'Insufficient scope: services:write required.'], 403);
        }

        $service->delete();

        // Clear service cache on write
        Cache::flush();

        return response()->json(['message' => 'Service deleted successfully.']);
    }
}

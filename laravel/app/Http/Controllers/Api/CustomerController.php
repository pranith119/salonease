<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a paginated listing of customers.
     * Requires scope: customers:read
     *
     * GET /api/customers?search=...&per_page=...
     */
    public function index(Request $request): JsonResponse
    {
        if (! $request->user()->tokenCan('customers:read')) {
            return response()->json(['message' => 'Insufficient scope: customers:read required.'], 403);
        }

        $query = Customer::query();

        if ($search = $request->input('search')) {
            $query->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        $perPage = min((int) $request->input('per_page', 15), 100);
        $customers = $query->orderBy('full_name')->paginate($perPage);

        return response()->json($customers);
    }

    /**
     * Store a newly created customer.
     * Requires scope: customers:write
     *
     * POST /api/customers
     */
    public function store(Request $request): JsonResponse
    {
        if (! $request->user()->tokenCan('customers:write')) {
            return response()->json(['message' => 'Insufficient scope: customers:write required.'], 403);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer created successfully.',
            'customer' => $customer,
        ], 201);
    }

    /**
     * Display the specified customer.
     * Requires scope: customers:read
     *
     * GET /api/customers/{customer}
     */
    public function show(Request $request, Customer $customer): JsonResponse
    {
        if (! $request->user()->tokenCan('customers:read')) {
            return response()->json(['message' => 'Insufficient scope: customers:read required.'], 403);
        }

        return response()->json(['customer' => $customer]);
    }

    /**
     * Update the specified customer.
     * Requires scope: customers:write
     *
     * PUT /api/customers/{customer}
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        if (! $request->user()->tokenCan('customers:write')) {
            return response()->json(['message' => 'Insufficient scope: customers:write required.'], 403);
        }

        $validated = $request->validate([
            'full_name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Customer updated successfully.',
            'customer' => $customer->fresh(),
        ]);
    }

    /**
     * Remove the specified customer.
     * Requires scope: customers:write
     *
     * DELETE /api/customers/{customer}
     */
    public function destroy(Request $request, Customer $customer): JsonResponse
    {
        if (! $request->user()->tokenCan('customers:write')) {
            return response()->json(['message' => 'Insufficient scope: customers:write required.'], 403);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully.']);
    }
}

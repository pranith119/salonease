<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    /**
     * Issue a new Sanctum API token.
     *
     * POST /api/login
     * Body: { "email": "...", "password": "...", "scopes": ["customers:read", ...] }
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'scopes' => ['sometimes', 'array'],
            'scopes.*' => ['string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Allowed scopes
        $allowedScopes = [
            'customers:read',
            'customers:write',
            'services:read',
            'services:write',
        ];

        $requestedScopes = $request->input('scopes', $allowedScopes);
        $validScopes = array_intersect($requestedScopes, $allowedScopes);

        $token = $user->createToken('api-token', $validScopes);

        return response()->json([
            'message' => 'Token generated successfully.',
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'scopes' => $validScopes,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 200);
    }

    /**
     * Revoke the current API token.
     *
     * POST /api/logout
     * Header: Authorization: Bearer {token}
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token revoked successfully.',
        ]);
    }
}

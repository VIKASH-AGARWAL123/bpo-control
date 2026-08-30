<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request): JsonResponse
    {
        $data = $request->validate([
            'organization_name' => ['required', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $result = DB::transaction(function () use ($data) {
            $org = Organization::create([
                'name' => $data['organization_name'],
                'slug' => str()->slug($data['organization_name']).'-'.str()->random(6),
                'timezone' => 'Asia/Kolkata',
            ]);

            $user = User::create([
                'organization_id' => $org->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'organization_owner',
                'is_active' => true,
            ]);

            return [$org, $user];
        });

        [$org, $user] = $result;
        $token = auth('api')->login($user);

        return response()->json($this->tokenResponse($token, $user, $org), 201);
    }

    public function signin(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = auth('api')->attempt(array_merge($credentials, ['is_active' => true]))) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        $user = auth('api')->user()->load('organization');

        return response()->json($this->tokenResponse($token, $user, $user->organization));
    }

    public function me(): JsonResponse
    {
        return response()->json(['user' => auth('api')->user()->load('organization')]);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Signed out successfully.']);
    }

    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();
        $user = auth('api')->user()->load('organization');

        return response()->json($this->tokenResponse($token, $user, $user->organization));
    }

    private function tokenResponse(string $token, User $user, ?Organization $organization): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
            'organization' => $organization,
        ];
    }
}

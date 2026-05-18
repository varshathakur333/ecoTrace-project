<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,collector',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'business_name' => 'required_if:role,collector|string|max:255',
            'license_no' => 'required_if:role,collector|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'address' => $request->address,
            'phone' => $request->phone,
            'business_name' => $request->role === 'collector' ? $request->business_name : null,
            'license_no' => $request->role === 'collector' ? $request->license_no : null,
            'is_verified' => false,
        ]);

        if ($user->role === 'collector') {
            VerificationRequest::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'license_no' => $request->license_no,
                'status' => 'pending',
            ]);
        }

        // Generate Sanctum / dummy API token
        // In full setups, use $user->createToken('auth_token')->plainTextToken
        // We will return a simulated secure token
        $token = base64_encode($user->email . '_ecotrace_token_secret');

        return response()->json([
            'success' => true,
            'message' => 'API Registration successful!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = base64_encode($user->email . '_ecotrace_token_secret');

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully via REST API!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }
}

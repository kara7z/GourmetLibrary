<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    function register(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'email' => ['required', 'email', 'max:256', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::default()],
        ]);

        $fields['password'] = Hash::make($fields['password']);
        $fields['role'] = User::query()->count() === 0 ? 'admin' : 'gourmand';

        $user = User::create($fields);

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'message' => 'User was created successfully',
            'user' => $user,
            'token' => $token,
        ], 202);
    }

    function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    function logout(Request $request)
    {

        $token = $request->bearerToken() ?: $request->input('token');

        if (!$token) {
            return response()->json([
                'message' => 'Unauthenticated. Provide a Bearer token or token field.'
            ], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json([
                'message' => 'Unauthenticated. Invalid token.'
            ], 401);
        }

        $accessToken->delete();
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}

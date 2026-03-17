<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

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

        $user = User::create($fields);

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'message' => 'User was created successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
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
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}

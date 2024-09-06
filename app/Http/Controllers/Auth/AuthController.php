<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function signIn(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user = Auth::user();

        if ($user->status == 0) {
            return response()->json([
                'message' => 'Usuario inactivo'
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name . ' ' . $user->paternal_surname . ' ' . $user->maternal_surname,
                'role' => $user->getRoleNames()[0],
                'email' => $user->email,
            ],
            'permissions' => implode('|', $user->getAllPermissions()->pluck('name')->toArray()),
        ]);
    }

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function user(Request $request)
    {
        $user =  $request->user();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name . ' ' . $user->paternal_surname . ' ' . $user->maternal_surname,
                'role' => $user->getRoleNames()[0],
                'email' => $user->email,
            ],
            'permissions' => implode('|', $user->getAllPermissions()->pluck('name')->toArray()),
        ]);
    }
}

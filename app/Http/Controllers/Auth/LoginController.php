<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate with the web guard
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication passed for web

            // Retrieve the authenticated user
            $user = Auth::guard('web')->user();

            // Create a new token for API usage
            $token = $user->createToken('magicport-app')->plainTextToken;

            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $user,
                'token' => $token,
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        // Log out user from web guard
        Auth::guard('web')->logout();

        // Invalidate API token if the user is authenticated
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            // Revoke the user's token
            $user->tokens()->delete(); // This will delete all tokens, or you can specify a particular token
        }

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

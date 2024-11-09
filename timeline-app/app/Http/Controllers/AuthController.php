<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => $request->password,
        ]);
    
        $token = JWTAuth::fromUser($user);

        session(['jwt_token' => $token]);

        return redirect('/timeline');
    }

    // User Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    
        // Store the token in the session
        session(['jwt_token' => $token]);
    
        // Redirect to the /timeline page
        return redirect('/timeline');
    }

    // Get Authenticated User
    public function me()
    {
        return response()->json(auth()->user());
    }

    // User Logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User logged out successfully']);
    }
}

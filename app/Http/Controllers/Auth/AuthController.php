<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return User::create([
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $request->role,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!$user = User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email does not exist'
            ]);
        }    
    
        if (!Auth::attempt($request->only("email", "password"))) {
            return response([
                'message' => 'Password is incorrect'
            ]);
        }
    
        $user = Auth::user();
    
        if (!$user->hasVerifiedEmail()) {
            return response([
                'message' => 'Email not verified'
            ]);
        }

        $token = $user->createToken('token')->plainTextToken;
    
        $cookie = cookie('jwt', $token, 60 * 40);
    
        return response([
            'message' => 'success',
            'token'=> $token
        ])->withCookie($cookie);
    }
    

    public function user()
    {
        return Auth::user()->role;
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }
}

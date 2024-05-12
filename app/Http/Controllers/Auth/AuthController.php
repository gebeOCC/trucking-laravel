<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($user = User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email exist'
            ]);
        } 

        $user = User::create([
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $request->role,
        ]);

        UserProfile::create([
            "user_id" => $user->id,
            "phone_number" => $request->phone_number,
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "date_of_birth" => $request->date_of_birth,
            "gender" => $request->gender,
            "province" => $request->province,
            "city" => $request->city,
            "barangay" => $request->barangay,
            "zip" => $request->zip,
        ]);

        return response(['message' => 'success']);
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
    
        // if (!$user->hasVerifiedEmail()) {
        //     return response([
        //         'message' => 'Email not verified'
        //     ]);
        // }

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

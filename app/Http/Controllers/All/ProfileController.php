<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function getProfile()
    {
        return User::where('users.id', Auth::user()->id)
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->addSelect('users_profile.profile_picture')
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->first();
    }
}

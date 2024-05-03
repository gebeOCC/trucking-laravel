<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'users_profile';

    protected $fillable = [
        'user_id',
        'profile_picture',
        'phone_number',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'province',
        'city',
        'barangay',
        'zip',
    ];
}

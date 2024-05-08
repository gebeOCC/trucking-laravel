<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class AdminBookingController extends Controller
{
    public function getBookings()
    {
        $bookings = Booking::select('booking.id','booking.pickup_type', 'booking.pickup_date', 'booking.pickup_time' )
        ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
        ->join('users_profile', 'users_profile.user_id', '=', 'booking.client_id')
        ->where('booking.booking_status', '=', 'pending')
        ->get();

        return response($bookings);
    }
}

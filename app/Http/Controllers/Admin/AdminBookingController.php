<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class AdminBookingController extends Controller
{
    public function getBookings()
    {
        $bookings = Booking::where('booking_status', '=', 'pending')
        ->get();

        return response([
            'message' => 'success',
            'bookings' => $bookings
        ]);
    }
}

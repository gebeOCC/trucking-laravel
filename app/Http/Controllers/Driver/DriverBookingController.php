<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Travel;
use Illuminate\Support\Facades\Auth;

class DriverBookingController extends Controller
{
    public function getDriverBookings(){
        return Travel::select('travels.id', 'travels.booking_id', 'booking.pickup_date', 'booking.pickup_time', 'pickup_type')
        ->join('booking', 'booking.id', '=', 'travels.booking_id')
        ->whereIn('travels.travel_status', ['in progress', 'delivering'])
        ->where('travels.driver_id', '=', Auth::user()->id)
        ->get();
    }
}

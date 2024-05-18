<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\UserProfile;
use App\Models\VehicleType;

class ClientController extends Controller
{
    public function getClients()
    {
        return User::select('users.id', 'users.email', 'users_profile.phone_number', 'users_profile.profile_picture')
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->selectRaw('COUNT(booking.id) AS total_bookings')
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->join('booking', 'users.id', '=', 'booking.client_id')
            ->where('users.role', '=', 'client')
            ->groupBy('users.id', 'users.email', 'users_profile.phone_number', 'users_profile.profile_picture', 'users_profile.first_name', 'users_profile.last_name')
            ->get();
    }

    public function getClientBookings($id)
    {
        $client_info =  UserProfile::select('profile_picture')
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->where('user_id', '=', $id)
            ->first();

        $bookings = Booking::select('booking.id', 'booking_status', 'pickup_type', 'pickup_date', 'pickup_time', 'booking.price', 'vehicle_type_name', 'distance')
            ->join('vehicle_type', 'booking.vehicle_type_id', '=', 'vehicle_type.id')
            ->get();

        $vehicle_types = VehicleType::select('vehicle_type_name')
            ->get();

        return response([
            'client_info' => $client_info,
            'bookings' => $bookings,
            'vehicle_types' => $vehicle_types,
        ]);
    }

    public function bookingDetails($id)
    {
        $booking = Booking::find($id);

        if ($booking->booking_status == 'pending') {
            return $booking;
        } else {
            return Booking::select('*')
                ->join('travels', 'booking.id', '=', 'travels.booking_id')
                ->selectRaw('travels.pickup_time AS travel_pickup_time')
                ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS driver_full_name')
                ->join('vehicles', 'travels.vehicle_id', '=', 'vehicles.id')
                ->join('users_profile', 'travels.driver_id', '=', 'users_profile.user_id')
                ->where('booking.id', '=', $id)
                ->first();
        }
    }
}

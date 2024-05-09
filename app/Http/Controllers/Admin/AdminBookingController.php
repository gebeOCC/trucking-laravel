<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Travel;
use App\Models\User;

class AdminBookingController extends Controller
{
    public function getBookings()
    {
        return Booking::select('booking.id', 'booking.pickup_type', 'booking.pickup_date', 'booking.pickup_time')
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->join('users_profile', 'users_profile.user_id', '=', 'booking.client_id')
            ->where('booking.booking_status', '=', 'pending')
            ->get();
    }

    public function getBooking($id)
    {
        $booking = Booking::select('booking.id', 'booking.pickup_date', 'booking.pickup_time', 'booking.distance', 'booking.price', 'booking.vehicle_type_id', 'booking.booking_status')
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->join('users_profile', 'users_profile.user_id', '=', 'booking.client_id')
            ->where('booking.id', '=', $id)
            ->first();

            if($booking->booking_status == 'approved'){
                return response (['booking' => $booking]);
            }

        $vehicle = Vehicle::select('vehicles.id', 'vehicles.model', 'vehicles.plate_number')
            ->with(['travels' => function ($query) {
                $query->select('travels.id', 'travels.vehicle_id', 'travels.booking_id', 'booking.pickup_date', 'booking.pickup_time')
                    ->join('booking', 'booking.id', '=', 'travels.booking_id')
                    ->whereIn('travel_status', ['in progress', 'delivering']);
            }])->where('vehicle_type_id', '=', $booking->vehicle_type_id, 'and', 'vehicle_status', '=', 'active')->get();

        $driver =
            User::select('users.id')
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->addSelect('drivers_info.license_expiry_date')
            ->with(['travels' => function ($query) {
                $query->select('travels.booking_id', 'travels.driver_id', 'booking.pickup_date', 'booking.pickup_time')
                    ->join('booking', 'booking.id', '=', 'travels.booking_id')
                    ->whereIn('travel_status', ['in progress', 'delivering']);
            }])
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->join('drivers_info', 'drivers_info.driver_id', '=', 'users.id')
            ->where('users.role', '=', 'driver')
            ->get();

        return response(['booking' => $booking, 'vehicle' => $vehicle, 'driver' => $driver]);
    }


    public function getVehicles($id)
    {
        return Vehicle::select('vehicles.id', 'vehicles.model', 'vehicles.plate_number')
            ->with(['travels' => function ($query) {
                $query->select('travels.id', 'travels.vehicle_id', 'travels.booking_id', 'booking.pickup_date', 'booking.pickup_time')
                    ->join('booking', 'booking.id', '=', 'travels.booking_id')
                    ->whereIn('travel_status', ['in progress', 'delivering']);
            }])->where('vehicle_type_id', '=', $id, 'and', 'vehicle_status', '=', 'active')->get();
    }

    public function getDrivers()
    {
        return User::select('users.id')
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->addSelect('drivers_info.license_expiry_date')
            ->with(['travels' => function ($query) {
                $query->select('travels.booking_id', 'travels.driver_id', 'booking.pickup_date', 'booking.pickup_time')
                    ->join('booking', 'booking.id', '=', 'travels.booking_id')
                    ->whereIn('travel_status', ['in progress', 'delivering']);
            }])
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->join('drivers_info', 'drivers_info.driver_id', '=', 'users.id')
            ->where('users.role', '=', 'driver')
            ->get();
    }

    public function addTravel(Request $request)
    {
        $travel = Travel::create([
            'booking_id' => $request->booking_id,
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
            'admin_id' => Auth::user()->id,
        ]);

        if ($travel) {
            Booking::find($request->booking_id)->update(['booking_status' => 'approved']);
        }

        return response(['message' => 'success']);
    }
}

<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Travel;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function addBooking(Request $request)
    {

        $id = Auth::user()->id;
        $file = $request->file('goods_photo');
        $randomName = md5(uniqid('', true));

        // Generate a unique filename based on current date and time
        $fileName = date('Ymd_His') . '_' . $randomName . '_' . $file->getClientOriginalName(); // Example: 20220501_165430_image.png

        // Move uploaded file to public/vehicle-type-images directory
        $file->move(public_path('goods-photo'), $fileName);

        Booking::create([
            'client_id' => $id,

            'pickup_type' => $request->pickup_type,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,

            'vehicle_type_id' => $request->vehicle_type_id,
            'goods_photo' => $fileName,


            'pickup_location_lat' => $request->pickup_location_lat,
            'pickup_location_lng' => $request->pickup_location_lng,
            'pickup_location_address' => $request->pickup_location_address,
            'sender_name' => $request->sender_name,
            'sender_contact_number' => $request->sender_contact_number,
            'pickup_location_details' => $request->pickup_location_details,

            'dropoff_location_lat' => $request->dropoff_location_lat,
            'dropoff_location_lng' => $request->dropoff_location_lng,
            'dropoff_location_address' => $request->dropoff_location_address,
            'recipient_name' => $request->recipient_name,
            'recipient_contact_number' => $request->recipient_contact_number,
            'dropoff_location_details' => $request->dropoff_location_details,

            'distance' => $request->distance,
            'duration' => $request->duration,
            'price' => $request->price,
        ]);

        return response(['message' => 'success']);
    }

    public function getClientBookings()
    {
        $pending = Booking::select('booking_status', 'pickup_date', 'pickup_time', 'pickup_location_address', 'dropoff_location_address', 'goods_photo')
            ->where('client_id', '=', Auth::user()->id)
            ->where('booking_status', '=', 'pending')
            ->orderBy('booking.created_at', 'desc')
            ->get();

        $approved = Booking::select('booking.id', 'booking_status', 'booking.pickup_date', 'booking.pickup_time', 'pickup_location_address', 'dropoff_location_address', 'goods_photo', 'travels.travel_status')
            ->join('travels', 'booking.id', '=', 'travels.booking_id')
            ->where('client_id', '=', Auth::user()->id)
            ->where('booking_status', '=', 'approved')
            ->orderBy('booking.updated_at', 'desc')
            ->whereNotIn('travels.travel_status', ['delivered'])
            ->get();

        $delivered = Booking::select('booking.id', 'booking_status', 'booking.pickup_date', 'booking.pickup_time', 'pickup_location_address', 'dropoff_location_address', 'goods_photo', 'travels.travel_status')
            ->join('travels', 'booking.id', '=', 'travels.booking_id')
            ->where('client_id', '=', Auth::user()->id)
            ->where('travels.travel_status', '=', 'delivered')
            ->orderBy('travels.updated_at', 'desc')
            ->get();

        return response(['pending' => $pending, 'approved' => $approved, 'delivered' => $delivered]);
    }

    public function getBookingDetails($id)
    {
        return Booking::select(
            'travels.vehicle_id',
            'travels.travel_status',
            'travels.pickup_goods_photo',
            'travels.dropoff_goods_photo',
            'travels.signature_image',
            'travels.dropoff_time',

            'booking.id',
            'booking.pickup_date',
            'booking.pickup_time',
            'booking.goods_photo',
            'booking.pickup_location_address',
            'booking.sender_name',
            'booking.sender_contact_number',
            'booking.pickup_location_details',
            'booking.dropoff_location_address',
            'booking.recipient_name',
            'booking.recipient_contact_number',
            'booking.dropoff_location_details',
            'booking.price',

            'vehicles.model',
            'plate_number',
        )
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->selectRaw('travels.pickup_time AS travel_pickup_time')
            ->join('travels', 'booking.id', '=', 'travels.booking_id')
            ->join('vehicles', 'travels.vehicle_id', '=', 'vehicles.id')
            ->join('users_profile', 'travels.driver_id', '=', 'users_profile.user_id')
            ->where('booking.id', $id)
            ->first();
    }
}

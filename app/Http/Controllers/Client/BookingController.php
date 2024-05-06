<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
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
}

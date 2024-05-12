<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Travel;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DriverBookingController extends Controller
{
    public function getDriverBookings()
    {
        return Travel::select('travels.id', 'travels.booking_id', 'booking.pickup_date', 'booking.pickup_time', 'pickup_type', 'booking.pickup_location_address', 'booking.dropoff_location_address', 'travels.travel_status')
            ->join('booking', 'booking.id', '=', 'travels.booking_id')
            ->where('travels.driver_id', '=', Auth::user()->id)
            ->orderBy('booking.pickup_date', 'asc')
            ->get();
    }

    public function getTravelDetails($id)
    {
        return Travel::select(
            'travels.id',
            'travels.booking_id',
            'travels.vehicle_id',
            'travels.travel_status',
            'travels.pickup_goods_photo',
            'travels.dropoff_goods_photo',
            'travels.signature_image',
            'travels.dropoff_time',

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
        ->selectRaw('travels.pickup_time AS travel_pickup_time')
            ->join('booking', 'booking.id', '=', 'travels.booking_id')
            ->join('vehicles', 'vehicles.id', '=', 'travels.vehicle_id')
            ->where('travels.id', $id)
            ->first();            
    }

    public function submitPickup (Request $request, $id) {
        $fileName = $this->uploadGoodsPhoto($request->file('pickup_goods_photo'));

        Travel::where('id', $id)->update([
            'pickup_goods_photo' => $fileName,
            'pickup_time' => $request->pickup_time,
            'travel_status' => 'delivering',
        ]);

        return response(['message' => 'success']);
    }

    public function submitDropoff(Request $request, $id) {
        $dopoffPhoto = $this->uploadGoodsPhoto($request->file('dropoff_goods_photo'));
        $signatureImage = $this->uploadSignature($request->file('signature_image'));

        Travel::where('id', $id)->update([
            'dropoff_goods_photo' => $dopoffPhoto,
            'signature_image' => $signatureImage,
            'dropoff_time' => $request->dropoff_time,
            'travel_status' => 'delivered',
        ]);

        return response(['message' => 'success']);
    }

    private function uploadGoodsPhoto($file)
    {
        $randomName = md5(uniqid('', true));
        $fileName = date('Ymd_His') . '_' . $randomName . '_' . $file->getClientOriginalName();
        $file->move(public_path('goods-photo'), $fileName);
        return $fileName;
    }

    private function uploadSignature($file)
    {
        $randomName = md5(uniqid('', true));
        $fileName = date('Ymd_His') . '_' . $randomName . '_' . $file->getClientOriginalName();
        $file->move(public_path('signature'), $fileName);
        return $fileName;
    }
}

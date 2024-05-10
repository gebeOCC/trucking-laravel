<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';

    protected $fillable = [
        'client_id',
        
        'pickup_type',
        'pickup_date',
        'pickup_time',

        'vehicle_type_id',
        'goods_photo',
        
        'pickup_location_lat',
        'pickup_location_lng',
        'pickup_location_address',
        'sender_name',
        'sender_contact_number',
        'pickup_location_details',

        'dropoff_location_lat',
        'dropoff_location_lng',
        'dropoff_location_address',
        'recipient_name',
        'recipient_contact_number',
        'dropoff_location_details',

        'distance',
        'duration',
        'price',

        'booking_status',
    ];
}

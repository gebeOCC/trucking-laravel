<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'travels';

    protected $fillable = [
        'booking_id',
        'driver_id',
        'vehicle_id',
        'admin_id',

        'pickup_time',
        'pickup_goods_photo',
        
        'dropoff_time',
        'dropoff_goods_photo',
        'signature_image',
        'travel_status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }
}

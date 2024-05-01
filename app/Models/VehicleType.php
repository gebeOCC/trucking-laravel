<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $table = 'vehicle_type';
    protected $fillable = [
        'vehicle_type_name',
        'vehicle_type_image',
        'weight_limit',
        'price',
    ];
}

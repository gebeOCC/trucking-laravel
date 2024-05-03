<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverInfo extends Model
{
    use HasFactory;

    protected $table = 'drivers_info';

    protected $fillable = [
        'driver_id',
        'license_number',
        'license_expiry_date',
    ];
}

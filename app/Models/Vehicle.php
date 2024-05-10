<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    protected $fillable = [
        'vehicle_type_id',
        'model',
        'plate_number',
        'vehicle_status',
    ];

    public function travels()
    {
        return $this->hasMany(Travel::class, 'vehicle_id');
    }
}

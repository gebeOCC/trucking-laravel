<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehiclesController extends Controller
{
    public function getVehicles()
    {
        return Vehicle::select('vehicles.*', 'vehicle_type.vehicle_type_name')
            ->join('vehicle_type', 'vehicle_type.id', '=', 'vehicles.vehicle_type_id')
            ->get();
    }

    public function getVehicle($id)
    {
        return Vehicle::find($id);
    }

    public function addVehicle(Request $request)
    {
        Vehicle::create($request->all());
        
        return response()->json(['message' => 'Vehicle addedd successfully']);
    }

    public function updateVehicle(Request $request, $id){
        Vehicle::find($id)->update($request->all());
        return response()->json(['message' => 'Vehicle updated successfully']);
    }

    public function deleteVehicle($id){
        Vehicle::find($id)->delete();
        return response()->json(['message' => 'Vehicle deleted successfully']);
    }

}

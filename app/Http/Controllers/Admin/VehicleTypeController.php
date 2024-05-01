<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleType;

class VehicleTypeController extends Controller
{
    public function addVehicleType(Request $request)
    {
        if ($request->hasFile('vehicle_type_image')) {
            $file = $request->file('vehicle_type_image');
            $randomName = md5(uniqid('', true));

            // Generate a unique filename based on current date and time
            $fileName = date('Ymd_His') . '_' . $randomName . '_' .$file->getClientOriginalName(); // Example: 20220501_165430_image.png

            // Move uploaded file to public/vehicle-type-images directory
            $file->move(public_path('vehicle-type-images'), $fileName);

            VehicleType::create([
                'vehicle_type_name'=> $request->vehicle_type_name,
                'vehicle_type_image' => $fileName,
                'weight_limit'=> $request->weight_limit,
                'price'=> $request->price,
            ]);

            return response()->json(['message' => 'Vehicle type addedd successfully'], 200);
        } else {
            return response()->json(['message' => 'No file uploaded'], 400);
        }
    }

    public function getVehicleTypes(){
        $vehicles = VehicleType::all();
        return response()->json($vehicles, 200);
    }

    public function getVehicleType($id)
    {
        // Assuming VehicleType model exists and uses Eloquent
        $vehicleType = VehicleType::find($id);

        if (!$vehicleType) {
            return response()->json(['error' => 'Vehicle type not found'], 404);
        }

        return response()->json($vehicleType);
    }

    public function updateVehicleType(Request $request, $id){
        if ($request->hasFile('vehicle_type_image')) {
            $file = $request->file('vehicle_type_image');
            $randomName = md5(uniqid('', true));

            // Generate a unique filename based on current date and time
            $fileName = date('Ymd_His') . '_' . $randomName . '_' . $file->getClientOriginalName(); // Example: 20220501_165430_image.png

            // Move uploaded file to public/vehicle-type-images directory
            $file->move(public_path('vehicle-type-images'), $fileName);

            VehicleType::where('id', $id)->update([
                'vehicle_type_name' => $request->vehicle_type_name,
                'vehicle_type_image' => $fileName,
                'weight_limit' => $request->weight_limit,
                'price' => $request->price,
            ]);

            return response()->json(['message' => 'Vehicle type updated successfully'], 200);
        } else {
            VehicleType::where('id', $id)->update([
                'vehicle_type_name'=> $request->vehicle_type_name,
                'weight_limit'=> $request->weight_limit,
                'price'=> $request->price,
            ]);
            return response()->json(['message' => 'Vehicle type updated successfully'], 200);
        }
    }

    public function deleteVehicleType($id){
        VehicleType::where('id', $id)->delete();
        return response()->json(['message'=> 'Vehicle type deleted successfully'],200);
    }
}

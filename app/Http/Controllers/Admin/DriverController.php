<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DriverInfo;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function getDrivers()
    {
        $drivers = User::select('users.id', 'users.email')
            ->selectRaw('CONCAT(users_profile.first_name, " ", users_profile.last_name) AS full_name')
            ->addSelect('users_profile.phone_number', 'users_profile.profile_picture', 'drivers_info.license_expiry_date')
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->join('drivers_info', 'drivers_info.driver_id', '=', 'users.id')
            ->where('users.role', '=', 'driver')
            ->get();

        return $drivers;
    }

    public function addDriver(Request $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        DriverInfo::create([
            'driver_id' => $user->id,
            'license_number' => $request->license_number,
            'license_expiry_date' => $request->license_expiry_date,
        ]);

        $fileName = $this->uploadProfilePicture($request->file('profile_picture'));

        UserProfile::create([
            'user_id' => $user->id,
            'profile_picture' => $fileName,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
            'zip' => $request->zip,
        ]);

        return response()->json([
            'message' => 'Driver added successfully'
        ]);
    }

    public function getDriverProfile($id)
    {
        return UserProfile::select('users_profile.*')
            ->where('users_profile.user_id', '=', $id)
            ->first();
    }

    public function updateUserProfile(Request $request, $id)
    {
        $user = UserProfile::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;
        $user->province = $request->province;
        $user->city = $request->city;
        $user->barangay = $request->barangay;
        $user->zip = $request->zip;
        $user->user_id = $request->user_id;
        $user->save();
    }

    public function getDriverCredentials($id)
    {
        return User::select('email')
            ->where('id', '=', $id)
            ->first();
    }

    public function updateCredentials(Request $request, $id)
    {
        $user = User::find($id);
        $user->email = $request->email;
    }

    public function getDriverInfo($id)
    {
        return DriverInfo::select('drivers_info.*')
            ->where('drivers_info.driver_id', '=', $id)
            ->first();
    }

    public function updateDriverProfile(Request $request, $id)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'barangay' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'zip' => 'required|string',
            'profile_picture' => 'nullable|file|max:2048', // Add file validation rules as needed
        ]);

        try {
            $userProfile = UserProfile::findOrFail($id);

            if ($request->hasFile('profile_picture')) {
                $fileName = $this->uploadProfilePicture($request->file('profile_picture'));
                $validatedData['profile_picture'] = $fileName;
            } else {
                // If no new profile picture is uploaded, keep the existing one
                $validatedData['profile_picture'] = $userProfile->profile_picture;
            }

            $userProfile->update($validatedData);

            return response()->json(['message' => 'Driver updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the driver profile'], 500);
        }
    }

    private function uploadProfilePicture($file)
    {
        $randomName = md5(uniqid('', true));
        $fileName = date('Ymd_His') . '_' . $randomName . '_' . $file->getClientOriginalName();
        $file->move(public_path('profile-pictures'), $fileName);
        return $fileName;
    }
}

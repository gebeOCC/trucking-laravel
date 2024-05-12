<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\VehiclesController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Driver\DriverBookingController;
use App\Http\Controllers\All\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('get-profile', [ProfileController::class, 'getProfile']);

    Route::get('get-vehicle-types', [VehicleTypeController::class, 'getVehicleTypes']);

    // Admin
    Route::post('add-vehicle-type', [VehicleTypeController::class, 'addVehicleType']);
    Route::get('get-vehicle-type/{id}', [VehicleTypeController::class, 'getVehicleType']);
    Route::post('update-vehicle-type/{id}', [VehicleTypeController::class, 'updateVehicleType']);
    Route::post('delete-vehicle-type/{id}', [VehicleTypeController::class, 'deleteVehicleType']);

    Route::get('get-vehicles', [VehiclesController::class, 'getVehicles']);
    Route::post('add-vehicle', [VehiclesController::class, 'addVehicle']);
    Route::get('get-vehicle/{id}', [VehiclesController::class, 'getVehicle']);
    Route::post('update-vehicle/{id}', [VehiclesController::class, 'updateVehicle']);
    Route::post('delete-vehicle/{id}', [VehiclesController::class, 'deleteVehicle']);

    Route::get('get-drivers', [DriverController::class, 'getDrivers']);
    Route::post('add-driver', [DriverController::class, 'addDriver']);
    Route::get('get-driver-profile/{id}', [DriverController::class, 'getDriverProfile']);
    Route::get('get-driver-credentials/{id}', [DriverController::class, 'getDriverCredentials']);
    Route::get('get-driver-info/{id}', [DriverController::class, 'getDriverInfo']);
    Route::post('update-driver-profile/{id}', [DriverController::class, 'updateDriverProfile']);

    Route::get('get-bookings', [AdminBookingController::class, 'getBookings']);
    Route::get('get-booking/{id}', [AdminBookingController::class, 'getBooking']);
    Route::get('get-vehicles-assign/{id}', [AdminBookingController::class, 'getVehicles']);
    Route::get('get-drivers-assign', [AdminBookingController::class, 'getDrivers']);
    Route::post('add-travel', [AdminBookingController::class, 'addTravel']);

    // Client
    Route::post('add-booking', [BookingController::class,'addBooking']);
    Route::get('get-client-bookings', [BookingController::class, 'getClientBookings']);
    Route::get('get-booking-details/{id}', [BookingController::class, 'getBookingDetails']);

    // Driver
    Route::get('get-driver-bookings', [DriverBookingController::class, 'getDriverBookings']);
    Route::get('get-travel-details/{id}', [DriverBookingController::class, 'getTravelDetails']);
    Route::post('submit-pickup/{id}', [DriverBookingController::class, 'submitPickup']);
    Route::post('submit-dropoff/{id}', [DriverBookingController::class, 'submitDropoff']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
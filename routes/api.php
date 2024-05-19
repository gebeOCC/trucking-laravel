<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\VehiclesController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Driver\DriverTravelController;
use App\Http\Controllers\All\ProfileController;

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
    Route::post('update-driver-info/{id}', [DriverController::class, 'updateDriverInfo']);
    Route::post('update-driver-credentials/{id}', [DriverController::class, 'updateDriverCredentials']);
    Route::get('driver-travels/{id}', [DriverController::class, 'getDriverTravels']);
    Route::get('travel-details/{id}', [DriverController::class, 'travelDetails']);

    Route::get('get-clients', [ClientController::class, 'getClients']);
    Route::get('get-client-bookings/{id}', [ClientController::class, 'getClientBookings']);
    Route::get('booking-details/{id}', [ClientController::class, 'bookingDetails']);

    Route::get('get-bookings', [AdminBookingController::class, 'getBookings']);
    Route::get('get-booking/{id}', [AdminBookingController::class, 'getBooking']);
    Route::get('get-vehicles-assign/{id}', [AdminBookingController::class, 'getVehicles']);
    Route::get('get-drivers-assign', [AdminBookingController::class, 'getDrivers']);
    Route::post('add-travel', [AdminBookingController::class, 'addTravel']);


    Route::get('get-dashboard-data', [DashboardController::class, 'getDashboardData']);

    // Client
    Route::post('add-booking', [BookingController::class,'addBooking']);
    Route::get('get-client-bookings', [BookingController::class, 'getClientBookings']);
    Route::get('get-booking-details/{id}', [BookingController::class, 'getBookingDetails']);

    // Driver
    Route::get('get-driver-bookings', [DriverTravelController::class, 'getDriverBookings']);
    Route::get('get-travel-details/{id}', [DriverTravelController::class, 'getTravelDetails']);
    Route::post('submit-pickup/{id}', [DriverTravelController::class, 'submitPickup']);
    Route::post('submit-dropoff/{id}', [DriverTravelController::class, 'submitDropoff']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
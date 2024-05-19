<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $startDate = Carbon::today();
        $endDate = Carbon::now()->addDays(6);

        $chartDelivery = Booking::selectRaw('pickup_date AS date, COUNT(*) AS count')
            ->whereBetween('pickup_date', [$startDate, $endDate])
            ->where('booking_status', 'approved')
            ->groupBy('pickup_date')
            ->orderBy('pickup_date')
            ->get();

        return response([
            'chartDelivery' => $chartDelivery
        ]);
    }
}

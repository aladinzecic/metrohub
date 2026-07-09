<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\Line;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $driver = auth()->user();

    $shift = Shift::with(['vehicle'])
                  ->where('driver_id', $driver->id)
                  ->whereDate('date', today())
                  ->whereIn('status', ['scheduled', 'active'])
                  ->first();

    $vehicle         = $shift?->vehicle;
    $expiry          = $vehicle ? Carbon::parse($vehicle->registration_expires_at) : null;
    $daysUntilExpiry = $expiry ? today()->diffInDays($expiry) : null;
    $isExpired       = $expiry ? $expiry->isPast() : false;

    return view('driver.driverdashboard', compact('driver', 'shift', 'vehicle', 'daysUntilExpiry', 'isExpired'));
}

}
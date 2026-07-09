<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Carbon\Carbon;

class ShiftController extends Controller
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

        $progress        = 0;
        $remainingTime   = null;
        $elapsedTime     = null;

        if ($shift) {
            $start   = Carbon::parse($shift->date . ' ' . $shift->start_time);
            $end     = Carbon::parse($shift->date . ' ' . $shift->end_time);
            $now     = Carbon::now();

            if ($now->between($start, $end)) {
                $totalMinutes   = $start->diffInMinutes($end);
                $elapsedMinutes = $start->diffInMinutes($now);
                $progress       = round(($elapsedMinutes / $totalMinutes) * 100);
                $remaining      = $now->diffInMinutes($end);
                $remainingTime  = floor($remaining / 60) . 'h ' . ($remaining % 60) . 'min';
                $elapsedTime    = floor($elapsedMinutes / 60) . 'h ' . ($elapsedMinutes % 60) . 'min';
            } elseif ($now->greaterThan($end)) {
                $progress = 100;
            }
        }

        return view('driver.myshift', compact(
            'driver', 'shift', 'vehicle',
            'daysUntilExpiry', 'isExpired',
            'progress', 'remainingTime', 'elapsedTime'
        ));
    }
}
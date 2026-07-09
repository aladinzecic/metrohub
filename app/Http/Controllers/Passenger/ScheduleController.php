<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\Station;

class ScheduleController extends Controller
{
    public function index()
    {
        $lines    = Line::where('status', 'active')->get();
        $stations = Station::all();

        return view('passenger.schedules', compact('lines', 'stations'));
    }
}
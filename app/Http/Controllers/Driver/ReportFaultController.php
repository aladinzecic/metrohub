<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Fault;
use App\Models\Shift;
use Illuminate\Http\Request;

class ReportFaultController extends Controller
{
    public function index()
    {
        $driver = auth()->user();

        $shift = Shift::with(['vehicle'])
                      ->where('driver_id', $driver->id)
                      ->whereDate('date', today())
                      ->whereIn('status', ['scheduled', 'active'])
                      ->first();

        $vehicle = $shift?->vehicle;

        $recentFaults = Fault::with(['vehicle'])
                             ->where('reported_by', $driver->id)
                             ->orderBy('reported_at', 'desc')
                             ->take(5)
                             ->get();

        return view('driver.reportfault', compact('driver', 'shift', 'vehicle', 'recentFaults'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id'  => 'required|exists:vehicles,id',
            'type'        => 'required|in:brakes,ac,validator,doors,seats,engine,lights,other',
            'severity'    => 'required|in:critical,moderate,minor',
            'description' => 'required|string|min:10',
        ]);

        Fault::create([
            'vehicle_id'  => $request->vehicle_id,
            'reported_by' => auth()->id(),
            'type'        => $request->type,
            'severity'    => $request->severity,
            'description' => $request->description,
            'status'      => 'open',
            'reported_at' => now(),
        ]);

        return back()->with('success', 'Fault reported successfully. Dispatcher has been notified.');
    }
}
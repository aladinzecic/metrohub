<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    //
public function addVehicle(Request $request)
{
    $data = $request->validate([
        'number' => 'required|integer',
        'plate' => 'required|unique:vehicles',
        'brand' => 'required',
        'model' => 'required',
        'year' => 'required|integer',
        'seat_count' => 'required|integer',
        'type' => 'required',
        'fuel_type' => 'required',
        'status' => 'required',
        'registration_expires_at' => 'required|date',
        'name' => 'required',
    ]);

    Vehicle::create($data);
    return redirect()->back()->with('success', 'Vehicle added successfully!');
}
    public function index(){
        $vehicleCount=Vehicle::count();
        $vehicleGarageCount=Vehicle::where('status','garage')->count();
        $vehicleReserveCount=Vehicle::where('status','reserve')->count();
        $vehicleCleaningCount=Vehicle::where('status','cleaning')->count();
        $vehicles=Vehicle::get();
        return view('dispatcher.dispatcherfleet',[
            'vehicleCount'=>$vehicleCount,
            'vehicleGarageCount'=>$vehicleGarageCount,
            'vehicleReserveCount'=>$vehicleReserveCount,
            'vehicleCleaningCount'=>$vehicleCleaningCount,
            'vehicles'=>$vehicles,
        ]);
    }
}

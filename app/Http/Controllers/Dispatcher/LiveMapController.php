<?php

namespace App\Http\Controllers\Dispatcher;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Line;
use App\Models\VehicleLocation;

class LiveMapController extends Controller
{
    public function index()
{
    $vehicles = Vehicle::with(['latestLocation'])
                       ->where('status', 'in_service')
                       ->get();

    $lines = Line::with(['stations' => function($q) {
        $q->orderBy('line_stations.order');
    }])->where('status', 'active')->get();

    $linesJson = $lines->map(function($line) {
        return [
            'id'       => $line->id,
            'code'     => $line->code,
            'name'     => $line->name,
            'stations' => $line->stations->map(function($s) {
                return [
                    'name' => $s->name,
                    'lat'  => $s->latitude,
                    'lng'  => $s->longitude,
                ];
            })->values()->toArray()
        ];
    })->values()->toArray();

    $vehiclesJson = $vehicles->map(function($v) {
        return [
            'id'    => $v->id,
            'name'  => $v->name,
            'plate' => $v->plate,
            'lat'   => $v->latestLocation ? $v->latestLocation->latitude : 44.8176,
            'lng'   => $v->latestLocation ? $v->latestLocation->longitude : 20.4569,
            'speed' => $v->latestLocation ? $v->latestLocation->speed : 0,
        ];
    })->values()->toArray();

    return view('dispatcher.livemap', compact('vehicles', 'lines', 'linesJson', 'vehiclesJson'));
}

    public function locations()
    {
        $locations = Vehicle::with([
            'latestLocation.line.stations' => function($q) {
                $q->orderBy('line_stations.order');
            }
        ])
        ->where('status', 'in_service')
        ->get()
        ->map(function($vehicle) {
            $loc      = $vehicle->latestLocation;
            $line     = $loc?->line;
            $stations = $line?->stations ?? collect();

            return [
                'id'                    => $vehicle->id,
                'name'                  => $vehicle->name,
                'plate'                 => $vehicle->plate,
                'lat'                   => $loc?->latitude,
                'lng'                   => $loc?->longitude,
                'speed'                 => $loc?->speed ?? 0,
                'line_code'             => $line?->code,
                'line_name'             => $line?->name,
                'current_station_index' => $loc?->current_station_index ?? 0,
                'stations'              => $stations->map(fn($s) => [
                    'name'      => $s->name,
                    'latitude'  => $s->latitude,
                    'longitude' => $s->longitude,
                ]),
            ];
        });

        return response()->json($locations);
    }
}
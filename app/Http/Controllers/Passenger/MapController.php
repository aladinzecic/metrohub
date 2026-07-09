<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Line;

class MapController extends Controller
{
    public function index()
    {
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

        return view('passenger.livemap', compact('linesJson'));
    }
}
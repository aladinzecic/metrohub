<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleLocation extends Model
{
        protected $fillable = [
    'vehicle_id',
    'latitude',
    'longitude',
    'speed',
    'recorded_at',
    'line_id',
    'current_station_index',
];

public function line()
{
    return $this->belongsTo(Line::class);
}

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'model',
        'year',
        'plate',
        'seat_count',
        'status',
        'fuel_type',
        'registration_expires_at',
        'number',
        'type'
    ];

    public function latestLocation()
{
    return $this->hasOne(VehicleLocation::class)->latestOfMany('recorded_at');
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineStation extends Model
{
    protected $fillable=[
        'line_id',
        'station_id',
        'order',
        'distance_from_pre',
    ];
}

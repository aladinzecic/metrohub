<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
    ];

public function stations()
{
    return $this->belongsToMany(Station::class, 'line_stations')
                ->withPivot('order')
                ->orderBy('line_stations.order');
}
}
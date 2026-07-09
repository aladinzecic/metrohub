<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fault extends Model
{
    protected $fillable = [
    'vehicle_id',
    'reported_by',
    'mechanic_id',
    'type',
    'severity',
    'description',
    'status',
    'reported_at',
    'resolved_at',
];

public function mechanic()
{
    return $this->belongsTo(User::class, 'mechanic_id');
}   

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
    protected $casts = [
    'reported_at'  => 'datetime',
    'resolved_at'  => 'datetime',
];
}
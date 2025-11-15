<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'bus_number',
        'capacity',
        'model',
        'year',
        'status',
        'driver_id',
        'bus_route_id',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function busRoute()
    {
        return $this->belongsTo(Route::class, 'bus_route_id');
    }
}

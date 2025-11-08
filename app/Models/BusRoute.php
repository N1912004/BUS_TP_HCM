<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    protected $fillable = [
        'route_number',
        'start_location',
        'end_location',
        'distance',
        'duration',
        'coords', // Add 'coords' to the fillable array
    ];

    protected $casts = [
        'coords' => 'array',
    ];
}

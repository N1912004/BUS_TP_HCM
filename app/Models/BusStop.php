<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusStop extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address', // Added address to fillable fields
    ];
}

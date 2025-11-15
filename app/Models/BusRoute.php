<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_tuyen',
        'diem_di',
        'diem_den',
        'ngay',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'is_active',
        'coords', // Add coords to fillable
    ];

    protected $casts = [
        'coords' => 'array', // Cast coords to array
    ];

    // Accessor to get the route name
    public function getNameAttribute()
    {
        return $this->ma_tuyen . ' (' . $this->diem_di . ' - ' . $this->diem_den . ')';
    }
}

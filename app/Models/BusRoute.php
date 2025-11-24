<?php
// app/Models/BusRoute.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    use HasFactory;

    protected $table = 'bus_routes';

    protected $fillable = [
        'ma_tuyen',
        'diem_di',
        'diem_den',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'ngay',
        'is_active',
        'coords',
        'latitude_di',
        'longitude_di',
        'latitude_den',
        'longitude_den',
    ];

    protected $casts = [
        'coords' => 'array',
    ];
}
?>

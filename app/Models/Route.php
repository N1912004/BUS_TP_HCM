<?php
// app/Models/Route.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
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
    ];
}
?>

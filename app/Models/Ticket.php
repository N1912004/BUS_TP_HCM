<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_type',
        'price',
        'has_student_card',
        'age',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

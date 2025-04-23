<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
    protected $fillable = [
        'room_id',
        'client_id',
        'accompany_number',
        'paid_price',
    ];
}

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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}

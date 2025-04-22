<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use  Notifiable,SoftDeletes;

    /**
     *
     * @var list<string>
     */
    protected $fillable = [
        'price',
        'reserved',
        'number',
        'capacity',
    ];

    /**
     *
     * @var list<string>
     */
    protected $hidden = [

    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}

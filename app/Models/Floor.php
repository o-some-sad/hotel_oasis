<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    protected $fillable = ['name', 'number', 'created_by'];

    /**
     * Get the user who created the floor.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the rooms for the floor.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Delete related rooms when a floor is deleted
        static::deleting(function ($floor) {
            $floor->rooms()->delete();
        });
    }
}

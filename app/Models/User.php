<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// Duplicate import removed
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use App\Observers\ClientObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ClientObserver::class])]
    class User extends Authenticatable implements BannableInterface
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, Bannable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'national_id',
        'avatar_img' ,
        'mobile',
        'country' ,
        'gender' ,
        'is_approved',
        'created_by',
        'approved_by' ,
        'banned_at' ,
        'deleted_at',
        'role',
        'last_login_in',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is banned
     * This is an alias for the isBanned method from the Bannable trait
     * to make the code more readable and maintainable
     *
     * @return bool
     */
    public function isUserBanned(): bool
    {
        return $this->isBanned();
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

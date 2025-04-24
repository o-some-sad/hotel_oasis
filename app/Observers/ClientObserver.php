<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\ClientApproved;

class ClientObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //

    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        \Log::info('Observer triggered', [
            'user_id' => $user->id,
            'role' => $user->role,
            'is_approved' => $user->is_approved,
            'wasChanged' => $user->wasChanged('is_approved')
        ]);
    
        if (
            $user->role === 'client' &&
            $user->wasChanged('is_approved') &&
            $user->is_approved
        ) {
            $user->notify(new ClientApproved());
        }
    }
    

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

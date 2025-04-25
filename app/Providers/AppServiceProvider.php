<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\ClientObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));
        User::observe(ClientObserver::class);
    }
}

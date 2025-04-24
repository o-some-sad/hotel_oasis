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
        User::observe(ClientObserver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));
    }
}

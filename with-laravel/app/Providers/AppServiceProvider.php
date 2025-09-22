<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Session\SessionManager;
use App\Services\CustomSessionHandler;

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
        // Register custom session handler
        $this->app->make(SessionManager::class)->extend('custom', function ($app) {
            return new CustomSessionHandler(config('session.lifetime'));
        });
    }
}

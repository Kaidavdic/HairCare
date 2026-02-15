<?php

namespace App\Providers;

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
        // Configure strong password validation
        \Illuminate\Validation\Rules\Password::defaults(function () {
            return \Illuminate\Validation\Rules\Password::min(8)
                ->mixedCase()      // Requires uppercase and lowercase
                ->numbers()        // Requires at least one number
                ->symbols()        // Requires at least one special character
                ->uncompromised(); // Checks against data breaches
        });
    }
}

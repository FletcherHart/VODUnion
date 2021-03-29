<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share('auth.user', function (){
            return[
                'loggedIn' => Auth::check(),
            ];
        });

        Inertia::share('user', function (){
            return Auth::user();
        });

        // Inertia::share('jetstream', function() {
        //     return Jetstream;
        // });

        Inertia::share('flash', function () {
            return [
                'updateStatus' => session('updateStatus'),
                'url' => session('url')
            ];
        });
    }
}

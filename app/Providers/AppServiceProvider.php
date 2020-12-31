<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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

        Inertia::share('flash', function () {
            return [
                'updateStatus' => session('updateStatus'),
            ];
        });
    }
}

<?php

namespace App\Providers;

use App\Models\Route;
use App\Models\RouteStop;
use App\Models\User;
use App\Observers\RouteObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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
//        RouteStop::observe(RouteObserver::class);
    }
}

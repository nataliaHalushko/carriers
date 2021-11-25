<?php

namespace App\Observers;

use App\Models\Route;

class RouteObserver
{
    /**
     * Handle the Route "created" event.
     *
     * @param  \App\Models\Route  $route
     * @return void
     */
    public function created(Route $route)
    {

    }

    /**
     * Handle the Route "updated" event.
     *
     * @param  \App\Models\Route  $route
     * @return void
     */
    public function updated(Route $route)
    {
        //
    }

    /**
     * Handle the Route "deleted" event.
     *
     * @param  \App\Models\Route  $route
     * @return void
     */
    public function deleted(Route $route)
    {
        dd($route->children()->get());
    }

    /**
     * Handle the Route "restored" event.
     *
     * @param  \App\Models\Route  $route
     * @return void
     */
    public function restored(Route $route)
    {
        //
    }

    /**
     * Handle the Route "force deleted" event.
     *
     * @param  \App\Models\Route  $route
     * @return void
     */
    public function forceDeleted(Route $route)
    {
        //
    }
}

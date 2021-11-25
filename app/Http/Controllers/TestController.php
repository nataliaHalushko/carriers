<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\TripRoute;
use Carbon\Carbon;

class TestController extends BaseController
{

    public function test(){
        $schedule = Schedule::with(['tickets.tripRoutes'])->find(2);
        $tickets = $schedule->tickets;

        $this->from_id = 'ChIJmRxzhYhULUcROI_krsns9Ak';
        $this->to_id = 'ChIJLV4ajcdXzUAR4KrZ5Cft1sk';
        $this->date = Carbon::now()->addDay();
        $this->count = 1;

        $test = Schedule::with(
            [
                'trip.tripRoute'=> function ($query){
                    $query->with('stop.settlement')->whereHas('stop.settlement',function ($query){
                        $query->wherePlaceId($this->from_id);
                    });
                    $query->with('child.stop.settlement')->whereHas('child.stop.settlement',function ($query){
                        if ( is_null($this->to_id))
                            $query->whereDistance(0);
                        else
                            $query->wherePlaceId($this->to_id);
                    });
                },
                'trip.tripRoute.child.stop.settlement',
                'trip.tripRoute.stop.settlement',
                'carrier',
                'bus',
                'driver',
                'tickets'
            ])
            ->whereHas('trip.tripRoute.child.stop.settlement',function ($query){
                if ( is_null($this->to_id))
                    $query->whereDistance(0);
                else
                    $query->wherePlaceId($this->to_id);
            })
            ->whereHas('trip.tripRoute.stop.settlement',function ($query){
                $query->wherePlaceId($this->from_id);
            })
            ->whereDate('date',$this->date)
            ->get();

        $test = TripRoute::with(['children','stop'])->find(9);

        dd($test->toArray());
    }

    public function getRouteFromTo(){

        $test = Route::with(['child.stop.settlement','stop.settlement'])
            ->whereHas('stop.settlement',function ($query){
                $query->wherePlaceId('ChIJiWRaGWVbLUcR_nTd7lnh1Ms');
            })
            ->whereHas('child.stop.settlement',function ($query){
                $query->wherePlaceId('ChIJO7iKWetwzUARhDgGVCJRefw');
            })
            ->get();

        return $this->sendResponse($test ?? '');
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Web\ScheduleResource;
use App\Http\Resources\Web\TripResource;
use App\Models\Carrier;
use App\Models\Schedule;
use App\Models\Trip;
use App\Models\TripRoute;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripController extends BaseController
{

    private $placeId;
    /**
     * @var mixed
     */
    private $from_id;
    /**
     * @var mixed
     */
    private $to_id;
    private $limit;
    /**
     * @var mixed
     */
    private $date;
    /**
     * @var mixed
     */
    private $count;


    public function timetablePlace($placeId){

        $this->from_id = $placeId;
        $this->limit = 5;

        return $this->sendResponse($this->getTipTable());
    }

    public function getCarriers(){
        $carriers = [];
        foreach (Carrier::get() as $carrier){
            $carriers[] = [
                'id' => $carrier->id,
                'name' => $carrier->name
            ];
        }
       return $this->sendResponse($carriers);

    }

    public function tripSearch(Request $request){

        $this->from_id = $request->get('from_id');
        $this->to_id = $request->get('to_id');
        $this->date = $request->get('date')??Carbon::now();
        $this->count = $request->get('count')??1;
        $this->carrier = $request->get('carrier');
        $this->limit = 100;

        $schedules = Schedule::with(
            [
                'trip.tripRoute.stop.settlement',
                'trip.tripRoute.parent.stop.settlement',
                'trip.tripRoute.info.stop.settlement',
                'carrier',
                'bus',
                'driver',
                'tickets'
            ])
            ->whereHas('trip.tripRoute.stop.settlement',function ($query){
                $query->wherePlaceId($this->from_id);
            })
            ->whereDate('date',$this->date);


        if ($this->carrier != null){
            $schedules = $schedules->whereHas('carrier',function ($query){
                $query->whereIn('id', $this->carrier);
            })
                ->get();
        }else{
            $schedules = $schedules->get();
        }

//        $schedules = $schedules->filter(function ($value){
//            return $value->trip->tripRoute->isNotEmpty();
//        });


//        return $this->sendResponse($schedules);

        return $this->sendResponse(ScheduleResource::collection($schedules));


    }
}

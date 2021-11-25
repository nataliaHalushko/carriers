<?php

namespace App\Http\Resources\Web;

use App\Models\Trip;
use App\Models\TripRoute;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $tripRoute = $this->trip->tripRoute->first();

        $tripRouteName = $this->trip->getRouteName();

        $stops =[];

        $departure = $this->trip->tripRoute->where('stop.settlement.place_id',$request->get('from_id'))->first();
        $arrival = $this->trip->tripRoute->where('stop.settlement.place_id',$request->get('to_id'))->first();

        $bookedSeat = array_unique( $this->tickets->pluck('seat')->toArray());

        return [
            'id' => $this->trip_id,
            'departure_time' => $departure ? $departure->departure : null,
            'arrival_time'=>$arrival->arrival,
            'trip_route'=>$tripRouteName,
            'place_name'=>$tripRoute->stop->name,
            'place_address'=>'',
            'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$tripRoute->stop->lat.",".$tripRoute->stop->lng."&query_place_id=".$tripRoute->stop->place_id,
            'price'=>$departure->info->where('stop.settlement.place_id',$request->get('to_id'))->first()->price,
            'max_count'=>17,//calc max free seat
            'detail'=>[
                'interval'=>$departure->info->where('stop.settlement.place_id',$request->get('to_id'))->first()->distance,
                'route'=>[
                    'trip_name'=>$tripRouteName,
                    'trip_type'=>'Регулярний',
                    'free_seat'=>17,
                    'from'=>[
                        'id'=>$tripRoute->stop_id,
                        'time'=>$tripRoute->departure,
                        'date'=>Carbon::create($this->date)->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$tripRoute->stop->lat.",".$tripRoute->stop->lng."&query_place_id=".$tripRoute->stop->place_id,
                        'settlement'=>$tripRoute->stop->settlement->name,
                        'address'=>$tripRoute->stop->name,
                    ],
                    'to'=>[
                        'id'=>$arrival->stop_id,
                        'time'=>$arrival->arrival,
                        'date'=>Carbon::create($this->date)->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$arrival->stop->lat.",".$arrival->stop->lng."&query_place_id=".$departure->stop->place_id,
                        'settlement'=>$arrival->stop->settlement->name,
                        'address'=>$arrival->stop->name,
                    ],
                    'stops'=>$stops

                ],
                'carrier'=>[
                    'name'=>$this->carrier->name,
                    'rating'=>4.5,
                    'trip_count'=>1,

                ],
                'bus'=>[
                    'model'=>$this->bus->model->brand->name.' '.$this->bus->model->name,
                    'count_seat'=>$this->bus->countSeat() - count($bookedSeat),
                    'schema'=>$this->bus->getSchema($bookedSeat),
                    'comfort'=>$this->bus->comfort
                ],
            ]
        ];
    }
}

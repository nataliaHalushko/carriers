<?php

namespace App\Http\Resources\Web;

use App\Models\Trip;
use App\Models\TripRoute;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tripRoute = $this->trip->getRouteName();
        $stops = TripRoute::where('trip_id',$this->trip_id)
            ->where('stop_id',$this->stop_id)
            ->whereNotIn('distance',[0,100,120])
            ->orderBy('price')->get();
        $stops = $stops->map(function ($item) use ($request){
            return [
                'id'=>$item->child->stop_id,
                'time'=>$item->child->arrival,
                'date'=>Carbon::create($request->get('date'))->format('d-M'),
                'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$item->child->stop->lat.",".$item->child->stop->lng."&query_place_id=".$item->child->stop->place_id,
                'settlement'=>$item->child->stop->settlement->name,
                'address'=>$item->child->stop->name,
            ];
        });
        return  [
            'id' => $this->trip_id,
            'departure_time'=>$this->child->departure,
            'arrival_time'=>$this->child->arrival,
            'trip_route'=>$tripRoute,
            'place_name'=>$this->stop->name,
            'place_address'=>'',
            'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$this->stop->lat.",".$this->stop->lng."&query_place_id=".$this->stop->place_id,
            'price'=>$this->price,
            'max_count'=>17,//calc max free seat
            'detail'=>[
                'interval'=>$this->distance,
                'route'=>[
                    'trip_name'=>$tripRoute,
                    'trip_type'=>'Регулярний',
                    'free_seat'=>17,
                    'from'=>[
                        'id'=>$this->stop_id,
                        'time'=>$this->departure,
                        'date'=>Carbon::create($request->get('date'))->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$this->stop->lat.",".$this->stop->lng."&query_place_id=".$this->stop->place_id,
                        'settlement'=>$this->stop->settlement->name,
                        'address'=>$this->stop->name,
                    ],
                    'to'=>[
                        'id'=>$this->child->stop_id,
                        'time'=>$this->child->arrival,
                        'date'=>Carbon::create($request->get('date'))->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$this->child->stop->lat.",".$this->child->stop->lng."&query_place_id=".$this->child->stop->place_id,
                        'settlement'=>$this->child->stop->settlement->name,
                        'address'=>$this->child->stop->name,
                    ],
                    'stops'=>$stops

                ],
                'carrier'=>[
                    'name'=>$this->trip->bus->carrier->name,
                    'rating'=>4.5,
                    'trip_count'=>1,

                ],
                'bus'=>[
                    'model'=>$this->trip->bus->model->brand->name.' '.$this->trip->bus->model->name,
                    'count_seat'=>$this->trip->bus->countSeat(),
                    'schema'=>$this->trip->bus->schema->template,
                    'comfort'=>$this->trip->bus->comfort
                ],
            ]


        ];
    }
}

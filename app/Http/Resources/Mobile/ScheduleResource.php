<?php

namespace App\Http\Resources\Mobile;

use App\Models\Carrier;
use App\Models\TripRoute;
use Carbon\Carbon;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $from = $this->trip->tripRoute->where('stop.settlement.place_id', '=', $request->get('from_id'))->first();

        if ($request->get('to_id')){
            $to = $this->trip->tripRoute->where('stop.settlement.place_id', '=', $request->get('to_id'))->first();
        }
        else {
            $to = $this->trip->tripRoute->last();
        }

        $place = explode(',',$from->stop->name);

        $child = TripRoute::getChild($from->id,$to->id);
        $current = $child;
        $current[] = $from->id;

        $tickets = [];

        foreach ($this->tickets as $ticket){
            $stop = collect(TripRoute::getChild($ticket->from_id, $ticket->to_id))->pluck('id');
            $stop[] = $ticket->from_id;

            if (!empty(array_intersect($current,$stop->toArray())))
                $tickets[] = $ticket;
        }


        foreach ($child as $stop){
            $stops[]=[
                'id'=>$stop->id,
                'time'=>$stop->time_from,
                'date'=>$request->get('date') ?
                    Carbon::create($request->get('date'))->format('d-M') :
                    Carbon::now()->format('d-M'),
                'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$stop->stop->lat.",".$stop->stop->lng."&query_place_id=".$stop->stop->place_id,
                'settlement'=>$stop->stop->settlement->name,
                'address'=>$stop->stop->name,
                'lat' => $stop->stop->lat,
                'lng' => $stop->stop->lng
            ];
        }

        $trip_count =0;
        $routes_count = 0;

        foreach ($this->carrier->buses as $bus){

            $trip_count += $bus->trips->count();

            foreach ($bus->trips as $trip){
                $routes_count += TripRoute::whereTripId($trip->id)->count();
            }
        }

        $trip_min = strtotime($to->arrival) > strtotime($from->departure) ? (strtotime($to->arrival) - strtotime($from->departure )) : 24 - (strtotime($from->departure) - strtotime($to->arrival));

         $int = new DateInterval("PT{$trip_min}S");


        $now = new DateTimeImmutable('now', new DateTimeZone('utc'));

        $trip_time = $now->diff($now->add($int))->format(' %h год. %i хв.');


        return [
            'id' => $this->id,
            'trip_time' => $trip_time,
            'departure_time' => $from->departure,
            'arrival_time' => $to->arrival,
            'trip_route' => $this->trip->getRouteName(),
            'place_name' => $place[0] ?? '',
            'place_address' => $place[1] ?? '',
            'link_maps' => "https://www.google.com/maps/search/?api=1&query=".$from->stop->lat.",".$from->stop->lng."&query_place_id=".$from->stop->place_id,
            'price' => $from->info->where('to_id',$to->stop_id)->first()->price,
            'max_count' => $this->bus->countSeat() ,
            'detail' => [
                'interval' => $from->info->where('to_id',$to->stop_id)->first()->distance,
                'route' => [
                    'trip_name'=>$this->trip->getRouteName(),
                    'trip_type'=>'Регулярний',
                    'free_seat'=>$this->bus->countSeat() - (count($tickets)),
                    'from'=>[
                        'id'=>$from->id,
                        'time'=>$from->arrival,
                        'date'=>Carbon::create($this->date)->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$from->stop->lat.",".$from->stop->lng."&query_place_id=".$from->stop->place_id,
                        'settlement'=>$from->stop->settlement->name,
                        'address'=>$from->stop->name,
                        'lat' => $from->stop->lat,
                        'lng' => $from->stop->lng
                    ],
                    'to' => [
                        'id'=>$to->id,
                        'time'=>$to->departure,
                        'date'=>Carbon::create($this->date)->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$to->stop->lat.",".$to->stop->lng."&query_place_id=".$to->stop->place_id,
                        'settlement'=>$to->stop->settlement->name,
                        'address'=>$to->stop->name,
                        'lat' => $to->stop->lat,
                        'lng' => $to->stop->lng

                    ],
                    'stop' => $stops ?? []

                ],
                'carrier' => [
                    'name'=>$this->carrier->name,
                    'rating'=>4.5,
                    'trip_count'=>$trip_count,
                    'routes_count' => $routes_count

                ],
                'bus'=>[
                    'model'=>$this->bus->model->brand->name.' '.$this->bus->model->name,
                    'count_seat'=>$this->bus->countSeat(),
                    'schema'=>$this->getSchema($tickets,$from->id),
                    'comfort'=>$this->bus->comfort
                ],
            ],
            'ticket_return'=>'https://burburbus.com.ua/publicoffer'
        ];


    }
}

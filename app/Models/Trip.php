<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Trip extends Model
{
    use HasFactory;

    const TYPE_REGULAR = 1;

    const MON = 1;
    const TUE = 2;
    const WED = 3;
    const THU = 4;
    const FRI = 5;
    const SAT = 6;
    const SUN = 7;

    protected $table = 'trips';

    protected $casts = [
        'schedule' => 'array',
    ];


    protected $fillable = [
        'bus_id',
        'number',
        'type',
        'removal',
        'schedule',
    ];

    public function bus(){
        return $this->belongsTo('App\Models\Bus');
    }

    public function tripRoute(){
        return $this->hasMany('App\Models\TripRoute');
    }
    public function test(){
        return $this->belongsToMany('App\Models\TripRoute');
    }

    public function getRouteName(): string
    {
        $tripRoute = $this->tripRoute()->with('stop.settlement')->get();
        return 'Рейс '.$this->number.' '.$tripRoute->first()->stop->settlement->name. ' - '. $tripRoute->last()->stop->settlement->name;
    }




    public static function getBookedSeat($data){


        $tickets = TripRoute::with('ticket')

            ->where('trip_id','=',$data['trip_id'])->get();



        $tickets = $tickets->filter(
            function ($item){
                return $item->ticket->isEmpty()?false:$item->ticket;
            }
        );

        $tickets = call_user_func_array('array_merge', $tickets->pluck('ticket')->toArray());
        $current = collect(TripRoute::getChild($data["from_id"], $data["to_id"]))->pluck('id');

        $seats = [];
        $count = [];
        $ids = [];
        foreach ($tickets as $ticket){
            $child = collect(TripRoute::getChild($ticket["from_id"], $ticket["to_id"]))->pluck('id');
            $count[] = array_intersect($current->toArray(),$child->toArray());
            $seats[]=$ticket['seat'];
            $ids[]=$ticket['id'];
        }


        return [
            "count"=>count(array_filter($count)),
            "seats"=>array_unique($seats),
            "ids"=>array_unique($ids)
        ];

    }



}

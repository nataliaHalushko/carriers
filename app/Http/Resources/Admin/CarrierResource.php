<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CarrierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $trips = [];

        $trips = $this->buses->map(function ($bus){
            return $bus->trips->map(function ($trip){
                return [
                    'id'=>$trip->id,
                    'name'=> $trip->number
                ];
            });
        })->toArray();

        $buses = $this->buses->map(function ($bus){
                return [
                    'id'=>$bus->id,
                    'name'=>$bus->number
                ];
        })->toArray();

        $drivers = $this->drivers->map(function ($driver){
                return [
                    'id'=>$driver->id,
                    'name'=> $driver->last_name.' '.$driver->first_name
                ];
            });

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'address'=>$this->address,
            'phone'=>$this->phone,
            'contact_person'=>$this->contact_person,
            'liqpay'=>$this->liqpay,
            'trips'=>call_user_func_array("array_merge",$trips),
            'buses'=>$buses,
            'drivers'=>$drivers
        ];
    }
}

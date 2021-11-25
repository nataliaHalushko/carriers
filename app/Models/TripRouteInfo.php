<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripRouteInfo extends Model
{
    use HasFactory;

    protected $table = 'trip_route_info';

    protected $fillable = [
        'trip_route_id',
        'to_id',
        'distance',
        'price'
    ];

    public function stop(){
        return $this->belongsTo('App\Models\Stop','to_id','id');
    }

    public function parent(){
        return $this->belongsTo(TripRoute::class,'trip_route_id','id');
    }

}

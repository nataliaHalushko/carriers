<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Ticket extends Model
{
    use HasFactory;

    const FREE = 'free';
    const BOOKED = 'booked';
    const IN_BUS = 'in_bus';
    const COME_IN = 'come_in';
    const GO_OUT = 'go_out';
    const IN_OUT = 'in_out';


    protected $fillable = [
        'user_id',
        'device_id',
        'schedules_id',
        'order_id',
        'from_id',
        'to_id',
        'fname',
        'lname',
        'phone',
        'email',
        'status',
        'price',
        'seat',
    ];


    public function tripRoutes(){
        return $this->belongsTo('App\Models\TripRoute','segment_id','id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    public function schedules(){
        return $this->belongsTo(Schedule::class,'schedules_id','id');
    }
}

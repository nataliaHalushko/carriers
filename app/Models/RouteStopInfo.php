<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStopInfo extends Model
{
    use HasFactory;

    protected $table = 'route_stop_info';

    protected $fillable = [
        'route_stop_id',
        'to_id',
        'distance',
        'price'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RouteStop extends Model
{
    use HasFactory;

    protected $table = 'route_stop';

    protected $casts = [
        'info'=>'array'
    ];

    protected $fillable = [
        'route_id',
        'stop_id',
        'parent_id',
        'info'
    ];

    public function child(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'child_stop_id','id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->child()->with('clild');
    }


    public function stop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Stop');
    }


    public static function getChild($from, $to='last'){

        $stop = self::query()->with('stop.settlement')->find($from);
        $distance = $stop->distance;
        do{
            $stops[]=$stop;
            $distance += $stop->distance;
            $stop = self::query()->with('stop.settlement')->whereChildStopId($stop->id)->first();
        }while( isset($stop) && isset($to) && $stop->id != $to);

        return [
            'stops'=> $stops,
            'distance' => $distance
        ];

    }
}

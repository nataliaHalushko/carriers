<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TripRoute extends Model
{
    use HasFactory;


    protected $table = 'trip_routes';

    protected $fillable = [
        'id',
        'trip_id',
        'stop_id',
        'child_stop_id',
        'distance',
        'price',
        'arrival',
        'departure',
        'place_id',
    ];

    public function stop(){
        return $this->belongsTo('App\Models\Stop');
    }
    public function info(){
        return $this->hasMany(TripRouteInfo::class);
    }

    public function trip(){
        return $this->hasOne('App\Models\Trip');
    }

    public function ticket(){
        return $this->hasMany('App\Models\Ticket','from_id','id');
    }

    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function parents(){
        return $this->parent()->with('parents');
    }

    public function child(){
        return $this->hasOne(__CLASS__, 'parent_id');
    }

    public function children()
    {
        return $this->child()->with('children');

    }

    public static function getChild($from, $to = 'last')
    {

        $stop = self::query()->find($from);

        do {
            $stop = self::query()->with(['child', 'parent', 'stop.settlement'])->whereParentId($stop->id)->first();

            if (isset($stop->child) && !is_null($stop->child)) {
                $stops[] = $stop;
            } else {
                return [];
            }
        } while (isset($stop->child) && isset($to) && $stop->child->id != $to);

        return $stops ?? [];

    }
}

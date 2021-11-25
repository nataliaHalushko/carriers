<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    const WC = 'wc';
    const AC = 'ac';
    const WIFI = 'wifi';
    const CHARGER = 'charger';
    const COFFEE = 'coffee';
    const SEAT = 'seat';


    protected $table = 'buses';

    protected $casts = [
        'comfort' => 'array',
        'numbering'=>'array'
    ];

    protected $fillable = [
        'schema_id',
        'model_id',
        'carrier_id',
        'numbering',
        'number',
        'comfort',
        'count_seat'
    ];

    public function model(){
        return $this->belongsTo('App\Models\Model');
    }


    public function carrier(){

        return $this->belongsTo('App\Models\Carrier');
    }

    public function schema(){

        return $this->belongsTo('App\Models\Schema');
    }

    public function trips(){
        return $this->hasMany(Trip::class);
    }

    public function drivers(){
        return $this->hasMany(Driver::class);
    }

    public function countSeat(){

        return max(call_user_func_array('array_merge',$this->numbering))['num'];
    }

    public function getSchema($bookedSeat){

        $schema = $this->schema->template;

        foreach ($schema as $i => $value){
            foreach ($value as $j => $item){
                $schema[$i][$j]['num'] = $this->numbering[$i][$j]['num'];
                if ( isset($bookedSeat)
                    && $schema[$i][$j]['type'] == Schema::TYPE_SEAT
                    && $schema[$i][$j]['num']
                    && in_array($schema[$i][$j]['num'],$bookedSeat)){
                        $schema[$i][$j]['status'] = Ticket::BOOKED;
                }
                elseif ($schema[$i][$j]['type'] == Schema::TYPE_SEAT)
                    $schema[$i][$j]['status'] = Ticket::FREE;
                else
                    $schema[$i][$j]['status'] = null;
            }
        }


        return $schema;
    }
}

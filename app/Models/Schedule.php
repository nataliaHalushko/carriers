<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Schedule extends Model
{
    use HasFactory;

    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }

    public function carrier(){
        return $this->belongsTo('App\Models\Carrier');
    }

    public function bus(){
        return $this->belongsTo('App\Models\Bus');
    }

    public function driver(){
        return $this->belongsTo('App\Models\Driver');
    }

    public function log(){
        return $this->hasMany(ScheduleLog::class,'schedule_id','id');
    }

    public function tickets(){
        return $this->hasMany('App\Models\Ticket','schedules_id','id');
    }

    public function getSchema($tickets = [], $stop = false){

        $schema = $this->bus->schema->template;

        $bookedSeat = collect($tickets)->pluck('seat');

        foreach ($schema as $i => $value){
            foreach ($value as $j => $item){
                $schema[$i][$j]['num'] = $this->bus->numbering[$i][$j]['num'];
                if ( isset($bookedSeat)
                    && $schema[$i][$j]['type'] == Schema::TYPE_SEAT
                    && $schema[$i][$j]['num']
                    && in_array($schema[$i][$j]['num'],$bookedSeat->toArray())){

                    $ticket = collect($tickets)->where('seat',$schema[$i][$j]['num'])->first();

                    if ((isset($schema[$i][$j]['status']) && $stop && $ticket->from_id == $stop && $schema[$i][$j]['status']=== Ticket::GO_OUT)
                        ||(isset($schema[$i][$j]['status']) && $stop && $ticket->to_id == $stop &&$schema[$i][$j]['status']=== Ticket::COME_IN))
                        $schema[$i][$j]['status'] = Ticket::IN_OUT;
                    elseif ($ticket->status == true)
                        $schema[$i][$j]['status'] = Ticket::IN_BUS;
                    elseif ($stop && $ticket->from_id == $stop)
                        $schema[$i][$j]['status'] = Ticket::COME_IN;
                    elseif($stop && $ticket->to_id == $stop)
                        $schema[$i][$j]['status'] = Ticket::GO_OUT;
                    else
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

    public function getStatus(){
        return ScheduleLog::getStatus(isset($this->log->last()->type)?$this->log->last()->type+10:0);
    }
}

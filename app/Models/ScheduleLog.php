<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleLog extends Model
{
    use HasFactory;

    protected $table = 'schedule_log';

    const TYPE_START = 0;
    const TYPE_STOP = 10;
    const TYPE_CONTINUE = 20;
    const TYPE_FINISH = 100;

    public function schedule(){
        return $this->hasMany(Schedule::class,'schedule_id','id');
    }

    public function stop(){
        return $this->hasMany(Stop::class,'stop_id','id');
    }

    public static function getStatus($status){

        switch ($status) {
            case 10:
                return 'stop';
            case 20:
                return 'continue';
            case 30:
                return 'stop';
            case 100:
                return 'finish';
            default:
                return 'start';
        }
    }
}

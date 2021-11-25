<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Stop extends Model
{
    use HasFactory;

    protected $table = 'stops';

    protected $fillable = [
        'settlement_id',
        'name',
        'lat',
        'lng',
        'place_id',
    ];

    public function settlement(){
        return $this->belongsTo('App\Models\Settlement');
    }
}

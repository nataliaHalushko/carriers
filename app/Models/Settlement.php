<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Settlement extends Model
{
    use HasFactory;

    protected $table = 'settlements';

    protected $fillable = [
        'place_id',
        'region_id',
        'name',
        'lat',
        'lng',
        'postal_code'
    ];

    public function region(){
        return $this->belongsTo('App\Models\Region');
    }
}

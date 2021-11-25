<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';


    protected $fillable = [
        'name',
    ];

    public function regions(){
        return $this->hasMany('App\Models\Region');
    }

    public function settlements(){
        return $this->hasManyThrough('App\Models\Settlement', 'App\Models\Region');
    }
}

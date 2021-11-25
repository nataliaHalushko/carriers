<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Carrier extends Model
{
    use HasFactory;

    protected $table = 'carriers';

    protected $fillable = [
        'name',
        'phone',
        'address',
        'contact_person',
        'refund_conditions',
        'liqpay'
    ];

    public function buses(){
        return $this->hasMany(Bus::class);
    }

    public function drivers(){
        return $this->hasMany(Driver::class);
    }

    public function getCountTrips(){

        return  $this->buses()->trips()->count();
    }
}

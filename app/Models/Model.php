<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as  ParentModel;


class Model extends ParentModel
{
    use HasFactory;

    protected $table = 'models';

    protected $fillable = [
        'brand_id',
        'name',
    ];

    public function brand(){
        return $this->belongsTo('App\Models\Brand');
    }

}

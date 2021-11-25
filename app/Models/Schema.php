<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Schema extends Model
{
    use HasFactory;

    const TYPE_SEAT = 'seat';
    const TYPE_PASS = 'pass';
    const TYPE_STAND = 'stand';


    protected $table = 'schemas';

    protected $casts = [
        'template' => 'array',
    ];


    protected $fillable = [
        'name',
        'template'
    ];
}

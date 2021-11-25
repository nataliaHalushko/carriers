<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Driver extends Model
{
    use HasFactory;

    const CATEGORIES = [
        'A1','A','B1','B','C1','C','D1','D','BE','C1E','CE','D1E','DE','T',
    ];

    protected $table = 'drivers';

    protected $casts = [
        'category' => 'array',
    ];

    protected $fillable = [
        'carrier_id',
        'last_name',
        'first_name',
        'surname',
        'licence',
        'date_licence',
        'category',
        'date_medical',
        'phone'
    ];

    public function carrier(){
        return $this->belongsTo('App\Models\Carrier');
    }
}

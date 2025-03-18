<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZoningStrategicLocationModel extends Model
{
    protected $table =  'zoning_strategic_location';
    protected $fillable = [
        'zoning_id',
        'strategic_location',
        'distance',
        'distance_type',  //KM, M, CM dll
    ];
}

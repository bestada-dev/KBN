<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyFacilityModel extends Model
{
    protected $table =  'property_facility';
    protected $fillable = [
        'property_id',
        'facility',
    ];
}

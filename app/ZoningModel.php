<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZoningModel extends Model
{
    protected $table =  'zoning';
    protected $fillable = [
        'zone_name',
        'address',
        'link_map',
    ];

    public function strategicLocation()
    {
        return $this->hasMany(ZoningStrategicLocationModel::class, 'zoning_id');
    }
}

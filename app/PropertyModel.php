<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyModel extends Model
{
    protected $table =  'property';
    protected $fillable = [
        'category_id',
        'zona_id',
        'property_address',
        'property_location_link',
        'block',
        'type', //Bonded & General
        'land_area',
        'building_area',
        'type_upload', // link & upload_vidio
        'url',
        'vidio',
        'desc',
        'layout',
        'status', //Available & NotAvailable
        'total_viewer',
    ];


    public function getCategory()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }

    public function getZoning()
    {
        return $this->belongsTo(ZoningModel::class, 'category_id');
    }


    public function getFacility()
    {
        return $this->hasMany(PropertyFacilityModel::class, 'property_id');
    }

    public function getAttachment()
    {
        return $this->hasMany(PropertyAttachModel::class, 'property_id');
    }


    public function countViewer()
    {
        return $this->hasMany(Visitor::class, 'property_id');
    }
}

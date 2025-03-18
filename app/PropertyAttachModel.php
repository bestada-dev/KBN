<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyAttachModel extends Model
{
    protected $table =  'property_attach';
    protected $fillable = [
        'property_id',
        'detail_photo',
    ];
}

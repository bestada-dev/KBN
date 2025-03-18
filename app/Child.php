<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $table = "child";
    protected $fillable = [
        'landing_page_setting_id',
        'title1_id',
        'title1_en',
        'title2_id',
        'title2_en',
        'description1_id',
        'description1_en',
        'description2_id',
        'description2_en',
        'image',
    ];
}

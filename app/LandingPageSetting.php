<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LandingPageSetting extends Model
{
    protected $table = "landing_page_settings";
    protected $fillable = [
        'section',
        'title1_id',
        'title2_id',
        'title1_en',
        'title2_en',
        'description1_id',
        'description2_id',
        'description1_en',
        'description2_en',
        'photo',
        'video',
        'address',
        'email',
        'phone',
        'whatsapp',
        'tiktok',
        'facebook',
        'instagram',
        'twitter',
        'page_type',
    ];


    protected static function booted()
    {
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('section', 'asc');
        });
    }
}

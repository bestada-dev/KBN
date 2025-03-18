<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BenefitTitle extends Model
{
    protected $table = 'benefit_titles';

    protected $fillable = [
    	'image',
        'title',
        'sub_title'
    ];
}

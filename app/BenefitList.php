<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BenefitList extends Model
{
    protected $table = 'benefit_lists';

    protected $fillable = [
    	'content',
    ];
}

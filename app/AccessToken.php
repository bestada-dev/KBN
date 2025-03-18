<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $table = 'access_token';

    public $timestamps = false;

    protected $fillable = [
        'id', 'token'
    ];

}

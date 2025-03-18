<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogEmail extends Model
{
    protected $table = 'log_email';

    public $timestamps = false;

    protected $fillable = [
    	'status', 'type', 'to', 'message', 'data'
    ];
}

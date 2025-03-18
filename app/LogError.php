<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogError extends Model
{
   protected $table = 'log_error';

    public $timestamps = false;

    protected $fillable = [
    	'type', 'path', 'function', 'error', 'status'
    ];
}

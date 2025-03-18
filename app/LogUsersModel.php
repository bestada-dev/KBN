<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogUsersModel extends Model
{
    protected $table  = 'log_user';
    protected $fillable = [
        'user_id',
        'user_name',
        'date',
        'type',
        'activity',
    ];
}

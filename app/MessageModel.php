<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{
    protected $table  = 'message';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'message',
    ];
}

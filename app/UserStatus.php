<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    protected $fillable = [
        'status_name',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_status_id');
    }
}

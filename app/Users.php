<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
// OLD : use Illuminate\Database\Eloquent\Model;

// OLD : class Users extends Model
class Users extends Authenticatable
{
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'user_status_id',
        'is_new',
        'token',
        'is_admin',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function userStatus()
    {
        return $this->belongsTo(UserStatus::class, 'user_status_id');
    }
}

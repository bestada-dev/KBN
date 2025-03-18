<?php

/************ Cara Jalanin Manual ********
php artisan db:seed --class=_3UsersTableSeeder
/*****************************************/

use Illuminate\Database\Seeder;
use App\Users;
use App\CompanyModel;

class _3UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create the first specific admin user
        Users::create([
            'name' => 'Admin',
            'phone_number' => '021877654',
            'email' => 'admin@bestada.com',
            'password' => bcrypt('asdfgh'), // Remember to hash passwords
            'user_status_id' => 1,
            'is_new' => false,
            'token' => __generateToken(),
            'is_admin' => true,
            'role_id' => 1,
        ]);

    }
}


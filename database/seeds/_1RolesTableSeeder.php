<?php

/************ Cara Jalanin Manual ********
php artisan db:seed --class=_1RolesTableSeeder
/*****************************************/

use Illuminate\Database\Seeder;
use App\Role;

class _1RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::create(['role_name' => 'Superadmin']);
        // Role::create(['role_name' => 'Vendor']);
        // Role::create(['role_name' => 'Karyawan']);
        // Role::create(['role_name' => 'Perusahaan']);
        // Add more roles as needed
    }
}

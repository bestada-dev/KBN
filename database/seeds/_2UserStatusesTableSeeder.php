<?php


/************ Cara Jalanin Manual ********
php artisan db:seed --class=_2UserStatusesTableSeeder
/*****************************************/

use Illuminate\Database\Seeder;
use App\UserStatus;

class _2UserStatusesTableSeeder extends Seeder
{
    public function run()
    {
        $statuses = ['Active', 'Inactive'];

        foreach ($statuses as $status) {
            UserStatus::create([
                'status_name' => $status
            ]);
        }
    }
}


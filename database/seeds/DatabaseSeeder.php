<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(_1RolesTableSeeder::class);
        $this->call(_2UserStatusesTableSeeder::class);
        $this->call(_3UsersTableSeeder::class);
        // $this->call(_4ProvinceSeeder::class);
        // $this->call(_5CitySeeder::class);
        $this->call(_6CategorySeeder::class);
        $this->call(_7ZoningSeeder::class);
        $this->call(_8PropertySeeder::class);
        $this->call(_9BenefitTitleSeeder::class);
        $this->call(_10BenefitListSeeder::class);


    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class _7ZoningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('zoning')->insert([
            [
                'zone_name' => 'Valuable Proposals',
                'address' => 'Valuable Proposals',
                'link_map' => 'Valuable Proposals',
            ],
        ]);
    }
}

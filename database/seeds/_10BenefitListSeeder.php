<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class _10BenefitListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('benefit_lists')->insert([
            [
                'content' => 'Parking Space',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Parking Space 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Parking Space 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Parking Space 4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'Parking Space 5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

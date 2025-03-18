<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class _8PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    // property
    // property_attach
    // property_pacility
    {
        DB::table('property')->insert([
            [
                'category_id' => 1,
                'zona_id' => 1,
                'property_address' => 'Valuable Proposals',
                'property_location_link' => 'Valuable Proposals',
                'block' => 'Valuable Proposals',
                'type' => 'Valuable Proposals',
                'land_area' => 'Valuable Proposals',
                'building_area' => 'Valuable Proposals',
                'type_upload' => 'Valuable Proposals',
                'url' => 'Valuable Proposals',
                'vidio' => 'Valuable Proposals',
                'desc' => 'Valuable Proposals',
                'layout' => 'Valuable Proposals',
                'status' => 'Valuable Proposals',
                'total_viewer' => 'Valuable Proposals',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        DB::table('property_facility')->insert([
            [
                'property_id' => 1,
                'facility' => 'Valuable Proposals',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        DB::table('property_attach')->insert([
            [
                'property_id' => 1,
                'detail_photo' => 'Valuable Proposals',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

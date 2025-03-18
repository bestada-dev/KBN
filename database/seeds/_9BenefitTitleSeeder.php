<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class _9BenefitTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('benefit_titles')->insert([
            [
                'image' => 'https://storage.googleapis.com/a1aa/image/qsb1FeZjCq0TGC5nNvAB40ZQyonFlGBdw8KWjCFBpDP3CB8JA.jpg',
                'title' => '1965 S Crescent Warehouse',
                'sub_title' => 'Agent hen an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

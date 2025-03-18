<?php

use Illuminate\Database\Seeder;
use App\CategoryModel;


class _6CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryInsert = [
            [
                "id" => 1,
                "name" => "Factory",
                "icon" => "-"
            ],
            [
                "id" => 2,
                "name" => "Industrial Land",
                "icon" => "-"
            ],
            [
                "id" => 3,
                "name" => "Warehouse",
                "icon" => "-"
            ],
            [
                "id" => 4,
                "name" => "Container Yard",
                "icon" => "-"
            ]
        ];

        // Memasukkan data ke database
        CategoryModel::insert($categoryInsert);

    }
}

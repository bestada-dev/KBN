<?php

use App\Visitor;
use App\PropertyModel;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Faker instance
        $faker = Faker::create();

        // Ambil ID properti yang tersedia
        $propertyIds = PropertyModel::pluck('id')->toArray();

        // Jika tidak ada properti, tampilkan error
        if (empty($propertyIds)) {
            throw new \Exception('No properties found. Please add properties to the PropertyModel table.');
        }

        // Menambahkan 10 data pengunjung secara manual
        foreach (range(1, 10) as $index) {
            Visitor::create([
                "ip" => $faker->ipv4,  // Menggunakan $faker untuk menghasilkan IP
                "user_id" => $faker->optional()->randomDigitNotNull,
                "property_id" => $faker->randomElement($propertyIds),
                "city_name" => $faker->city,
                "country_code" => $faker->randomElement(['id', 'us', 'jp', 'kr', 'dst']),
                "country_name" => $faker->country,
                "created_at" => $faker->dateTimeBetween('2024-12-11', '2024-12-20'),
                "updated_at" => now(),
            ]);
        }
    }
}

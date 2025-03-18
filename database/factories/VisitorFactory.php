<?php

namespace Database\Factories;

use App\PropertyModel;
use App\Visitor;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

class VisitorFactory extends Factory
{
    protected $model = Visitor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $propertyIds = PropertyModel::pluck('id')->toArray();

        if (empty($propertyIds)) {
            throw new \Exception('No properties found. Please add properties to the PropertyModel table.');
        }

        return [
            "ip" => $this->faker->ipv4,
            "user_id" => $this->faker->optional()->randomDigitNotNull,
            "property_id" => $this->faker->randomElement($propertyIds),
            "city_name" => $this->faker->city,
            "country_code" => $this->faker->randomElement(['id', 'us', 'jp', 'kr', 'dst']),
            "country_name" => $this->faker->country,
            "created_at" => $this->faker->dateTimeBetween('2024-12-11', '2024-12-20'),
            "updated_at" => now(),
        ];
    }
}

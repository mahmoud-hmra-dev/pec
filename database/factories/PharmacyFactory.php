<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Factories\Factory;


class PharmacyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pharmacy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' => $this->faker->firstName,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'country_id' => Country::inRandomOrder()->first()->id,
        ];
    }
}

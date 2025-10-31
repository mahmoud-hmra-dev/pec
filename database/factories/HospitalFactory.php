<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hospital;


class HospitalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hospital::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $countries = Country::all()->pluck('id')->toArray();
        return [
            'name' => $this->faker->firstName,
			'contact_person' => $this->faker->name,
			'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'country_id'=> $countries[array_rand($countries)],
        ];
    }
}

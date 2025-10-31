<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hospital;


class DoctorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Doctor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
			'phone' => $this->faker->phoneNumber,
        ];
    }
}

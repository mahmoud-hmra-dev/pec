<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email'=> $this->faker->email,
            'password'=> Hash::make('12345678'),
            'personal_email'=> $this->faker->email,
            'country_id'=> Country::inRandomOrder()->first()->id,
            'city' => $this->faker->city,
            'address'=> $this->faker->address,

        ];
    }
}

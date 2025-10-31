<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Drug;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class DrugFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Drug::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $clients = Client::all()->pluck('id')->toArray();

        return [
            'name' => $this->faker->name,
			'client_id'=> $clients[array_rand($clients)],
			'api_name' => Str::random(4),
			'drug_initial' => Str::random(7),
			'drug_id'=> Str::random(7),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\ContractTypeEnum;
use App\Enums\ProgramTypeEnum;
use App\Models\Country;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Program;
use App\Models\Client;
use App\Models\User;

class ServiceProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> $this->faker->randomFloat(2,0,999),
            'city'=> $this->faker->city,
            'country_id'=> Country::inRandomOrder()->first()->id,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\ProgramTypeEnum;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Program;
use App\Models\Client;
use App\Models\User;

class ProgramFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Program::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $service_type_id = ServiceType::where('name','Project Manager')->first()->id;

        return [
            'name' => $this->faker->name,
            'program_no' => $this->faker->name,
            'client_id' => Client::inRandomOrder()->first()->id,
            'service_provider_type_id' => ServiceProviderType::where('service_type_id',$service_type_id)->inRandomOrder()->first()->id,
            'map_id' => $this->faker->uuid,
            'started_at' => now(),
            'ended_at' => now()->addMonths(12),
        ];
    }
}

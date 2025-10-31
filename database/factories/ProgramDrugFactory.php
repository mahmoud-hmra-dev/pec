<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Drug;
use App\Models\Program;
use App\Models\ProgramDrug;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class ProgramDrugFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProgramDrug::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $client_id = Client::inRandomOrder()->first()->id;
        $programs = Program::where('client_id',$client_id)->pluck('id')->toArray();
        $drugs = Drug::where('client_id',$client_id)->pluck('id')->toArray();

        return [
			'program_id'=> $programs[array_rand($programs)],
            'drug_id'=> $drugs[array_rand($drugs)],
        ];
    }
}

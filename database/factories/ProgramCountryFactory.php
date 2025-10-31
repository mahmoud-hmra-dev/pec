<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Country;
use App\Models\Drug;
use App\Models\Program;
use App\Models\ProgramCountry;
use App\Models\ProgramDrug;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class ProgramCountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProgramCountry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $programs = Program::pluck('id')->toArray();
        $countries = Country::pluck('id')->toArray();

        return [
			'program_id'=> $programs[array_rand($programs)],
            'country_id'=> $countries[array_rand($countries)],
        ];
    }
}

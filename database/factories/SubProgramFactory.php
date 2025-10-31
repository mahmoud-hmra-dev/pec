<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Drug;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name,
            'program_id'=> Program::inRandomOrder()->first()->id,
            'country_id'=> Country::inRandomOrder()->first()->id,
            'drug_id'=> Drug::inRandomOrder()->first()->id,
            'target_number_of_patients'=> Drug::inRandomOrder()->first()->id,
            'eligible'=>1,
            'has_calls'=>1,
            'has_visits'=>1,
            'has_FOC'=>1,

            'cycle_period'=>10,
            'cycle_number'=>5,
            'cycle_reminder_at'=>2,

            'visit_every_day'=> 10,
            'call_every_day'=> 10,
            'treatment_duration'=> 30,
            'is_follow_program_date'=>1,
            'program_initial'=>1,
            'start_date' => now(),
            'finish_date' => now()->addMonths(12),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Enums\ActivityTypeEnum;
use App\Enums\RoleEnum;
use App\Models\ActivityType;
use App\Models\Client;
use App\Models\Country;
use App\Models\Drug;
use App\Models\Nurse;
use App\Models\Pharmacy;
use App\Models\Physician;
use App\Models\Program;
use App\Models\QuestionCategory;
use App\Models\Specialty;
use App\Models\SubProgram;
use App\Models\User;
use Database\Factories\SubProgramFactory;
use Illuminate\Database\Seeder;

class SubProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pharmacy::factory(20)->create();
        QuestionCategory::create(['name'=>"Medical Info"]);
        QuestionCategory::create(['name'=>"Patient Exercises"]);
        QuestionCategory::create(['name'=>"Diet Program"]);
        QuestionCategory::create(['name'=>"Pharmacy"]);

        ActivityType::create(['name'=>ActivityTypeEnum::Visit]);
        ActivityType::create(['name'=>ActivityTypeEnum::Call]);

        Drug::factory(20)->create();
        SubProgram::factory(20)->create();
    }
}

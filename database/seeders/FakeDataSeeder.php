<?php

namespace Database\Seeders;

use App\Enums\ContractTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\ProgramTypeEnum;
use App\Enums\RoleEnum;
use App\Models\Client;
use App\Models\Country;
use App\Models\CountryServiceProvider;
use App\Models\DocumentType;
use App\Models\Drug;
use App\Models\Hospital;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Pharmacy;
use App\Models\Physician;
use App\Models\Program;
use App\Models\QuestionCategory;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\Specialty;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Patient Fake User
        $sub_programs = SubProgram::all();

        foreach ($sub_programs as $sub_program){
            for ($service_provider_type_id = 1 ; $service_provider_type_id < 9 ; $service_provider_type_id ++) {
                $country_service_provider = CountryServiceProvider::create([
                    'service_provider_type_id'=>$service_provider_type_id,
                    'country_id'=>$sub_program->country_id,
                    'sub_program_id'=>$sub_program->id,
                ]);
            }
        }

        $patient_user = User::create([
            'first_name' => "patient",
            'last_name' => "patient",
            'email' => "patient@clingroup.net",
            'password' => 'password',
            'personal_email' => 'patient@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);
        $patient = Patient::create([
            'id' => $patient_user->id,
            'patient_no' => rand(0,9999),
            'height' => 182,
            'weight' => 90,
            'BMI' => 32,
            'is_over_weight' => 1,
            'comorbidities' => 'comorbidities',
            'gender' => GenderEnum::MALE,
            'is_eligible' => 1,
            'reporter_name' => "test",
            'hospital_id' => Hospital::inRandomOrder()->first()->id,
        ]);
        $sub_program_id =  1;
        $sub_program_patient = $patient->sub_program_patients()->create([
            'sub_program_id'=>$sub_program_id,
        ]);
        $coordinators = CountryServiceProvider::where('sub_program_id', $sub_program_id)
            ->with(['service_provider_type', 'service_provider_type.service_type'])
            ->whereHas('service_provider_type.service_type', function ($query) {
                $query->where('name', 'Program Coordinator');
            })->inRandomOrder()->first();

        $nurses = CountryServiceProvider::where('sub_program_id', $sub_program_id)
            ->with(['service_provider_type', 'service_provider_type.service_type'])
            ->whereHas('service_provider_type.service_type', function ($query) {
                $query->where('name', 'Nurse');
            })->inRandomOrder()->first();

        $nurse = $sub_program_patient->patient_country_providers()->create([
            'country_service_provider_id' => $nurses->id,
        ]);

        $coordinator = $sub_program_patient->patient_country_providers()->create([
            'country_service_provider_id' => $coordinators->id,
        ]);
        $this->visits($sub_program_patient,$nurse,$coordinator);
        $this->foc_visits($sub_program_patient,$coordinator);

        $patient_user->assignRole(RoleEnum::PATIENT);


        $user1 = User::create([
            'first_name' => 'Rafi',
            'last_name' => 'Tesla',
            'uuid'=>uniqid(),
            'email' => 'mhamod@clingroup.net',
            'password' => 'password',
            'phone' => '+9619552266331',
            'personal_email' => 't@gmail.com',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $user1->assignRole(RoleEnum::PATIENT);

        $patient = Patient::create([
            'id' => $user1->id,
            'patient_no' => rand(0,9999),
            'height' => 182,
            'weight' => 90,
            'BMI' => 32,
            'is_over_weight' => 1,
            'comorbidities' => 'comorbidities',
            'gender' => GenderEnum::MALE,
            'is_eligible' => 1,
            'reporter_name' => "test",
            'hospital_id' => Hospital::inRandomOrder()->first()->id,
        ]);
        $sub_program_id =  2;
        $sub_program_patient = $patient->sub_program_patients()->create([
            'sub_program_id'=>$sub_program_id,
        ]);
        $coordinators = CountryServiceProvider::where('sub_program_id', $sub_program_id)
            ->with(['service_provider_type', 'service_provider_type.service_type'])
            ->whereHas('service_provider_type.service_type', function ($query) {
                $query->where('name', 'Program Coordinator');
            })->inRandomOrder()->first();

        $nurses = CountryServiceProvider::where('sub_program_id', $sub_program_id)
            ->with(['service_provider_type', 'service_provider_type.service_type'])
            ->whereHas('service_provider_type.service_type', function ($query) {
                $query->where('name', 'Nurse');
            })->inRandomOrder()->first();

        $nurse = $sub_program_patient->patient_country_providers()->create([
            'country_service_provider_id' => $nurses->id,
        ]);

        $coordinator = $sub_program_patient->patient_country_providers()->create([
            'country_service_provider_id' => $coordinators->id,
        ]);
        $this->visits($sub_program_patient,$nurse,$coordinator);
        $this->foc_visits($sub_program_patient,$coordinator);
    }

    public function visits($sub_program_patient , $nurse , $coordinator){
        $sub_program = SubProgram::where('id',$sub_program_patient->sub_program_id)->first();

        if($sub_program && $sub_program->has_visits && $sub_program->treatment_duration && $sub_program->visit_every_day && $sub_program->visit_every_day > 0){
            $start_date = Carbon::parse($sub_program_patient->created_at);
            if($sub_program->visit_every_day && $sub_program->visit_every_day > 0){
                $number_of_visits = $sub_program->treatment_duration/$sub_program->visit_every_day ;
                for ($i=0;$i<$number_of_visits ;$i++) {
                    $sub_program_patient->visits()->create([
                        'sub_program_id'=>$sub_program->id,
                        'activity_type_id'=>1,
                        'service_provider_type_id'=> $nurse ? $nurse->country_service_provider->service_provider_type_id : null,
                        'should_start_at'=>$start_date,
                    ]);
                    $start_date = $start_date->addDays($sub_program->visit_every_day);
                }
            }
        }

        if($sub_program && $sub_program->has_calls && $sub_program->treatment_duration && $sub_program->call_every_day && $sub_program->call_every_day > 0){
            $start_date = Carbon::parse($sub_program_patient->created_at);
            $number_of_visits = $sub_program->treatment_duration/$sub_program->call_every_day ;
            for ($i=0;$i<$number_of_visits ;$i++) {
                $sub_program_patient->visits()->create([
                    'sub_program_id'=>$sub_program->id,
                    'activity_type_id'=>2,
                    'service_provider_type_id'=>$coordinator ? $coordinator->country_service_provider->service_provider_type_id : null,
                    'should_start_at'=>$start_date,
                ]);
                $start_date = $start_date->addDays($sub_program->call_every_day);
            }
        }
    }

    public function foc_visits($sub_program_patient , $coordinator){
        $sub_program = SubProgram::where('id',$sub_program_patient->sub_program_id)->first();

        if($sub_program && $sub_program->has_FOC && $sub_program->cycle_period > 0 && $sub_program->cycle_number > 0 && $sub_program->call_every_day > 0){
            $start_date = Carbon::parse($sub_program_patient->created_at);
            $reminder_at = Carbon::parse($sub_program_patient->created_at)->subDays($sub_program->cycle_reminder_at);
            $number_of_visits = $sub_program->cycle_number ;
            for ($i=0;$i<$number_of_visits ;$i++) {
                $sub_program_patient->foc_visits()->create([
                    'sub_program_id'=>$sub_program->id,
                    'service_provider_type_id'=>$coordinator ? $coordinator->country_service_provider->service_provider_type_id : null,
                    'start_at'=>$start_date,
                    'reminder_at'=>$reminder_at,
                ]);
                $start_date = $start_date->addDays($sub_program->cycle_period);
                $reminder_at = $reminder_at->addDays($sub_program->cycle_period);
            }
        }
    }
}

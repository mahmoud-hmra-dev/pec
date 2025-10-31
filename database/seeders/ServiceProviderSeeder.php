<?php

namespace Database\Seeders;

use App\Enums\ContractTypeEnum;
use App\Enums\RoleEnum;
use App\Models\Country;
use App\Models\Program;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $nurse_user = User::create([
            'first_name' => "Ali",
            'last_name' => "Nurse",
            'email' => "nurse@clingroup.net",
            'password' => 'password',
            'personal_email' => 'nurse@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $nurse_user->assignRole(RoleEnum::NURSE);

        $nurse_service_provider = ServiceProvider::create([
            'user_id' => $nurse_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::NURSE)->first()->id,
            'service_provider_id'=>$nurse_service_provider->id
        ]);



        $nurse_user = User::create([
            'first_name' => "Rem",
            'last_name' => "Nurse",
            'email' => "rnurse@clingroup.net",
            'password' => 'password',
            'personal_email' => 'rnurse@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $nurse_user->assignRole(RoleEnum::NURSE);

        $nurse_service_provider = ServiceProvider::create([
            'user_id' => $nurse_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::NURSE)->first()->id,
            'service_provider_id'=>$nurse_service_provider->id
        ]);



        $nurse_user = User::create([
            'first_name' => "Mohamad",
            'last_name' => "Nurse",
            'email' => "mnurse@clingroup.net",
            'password' => 'password',
            'personal_email' => 'mnurse@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $nurse_user->assignRole(RoleEnum::NURSE);

        $nurse_service_provider = ServiceProvider::create([
            'user_id' => $nurse_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::NURSE)->first()->id,
            'service_provider_id'=>$nurse_service_provider->id
        ]);

        //Physician Fake User
        $physician_user = User::create([
            'first_name' => "physician",
            'last_name' => "physician",
            'email' => "physician@clingroup.net",
            'password' => 'password',
            'personal_email' => 'physician@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $physician_user->assignRole(RoleEnum::PHYSICIAN);

        $physician_provider = ServiceProvider::create([
            'user_id' => $physician_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::PHYSICIAN)->first()->id,
            'service_provider_id'=>$physician_provider->id
        ]);






        $physician_user = User::create([
            'first_name' => "Ryad",
            'last_name' => "physician",
            'email' => "rphysician@clingroup.net",
            'password' => 'password',
            'personal_email' => 'rphysician@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $physician_user->assignRole(RoleEnum::PHYSICIAN);

        $physician_provider = ServiceProvider::create([
            'user_id' => $physician_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::PHYSICIAN)->first()->id,
            'service_provider_id'=>$physician_provider->id
        ]);





        $physician_user = User::create([
            'first_name' => "Assad",
            'last_name' => "physician",
            'email' => "aphysician@clingroup.net",
            'password' => 'password',
            'personal_email' => 'aphysician@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $physician_user->assignRole(RoleEnum::PHYSICIAN);

        $physician_provider = ServiceProvider::create([
            'user_id' => $physician_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::PHYSICIAN)->first()->id,
            'service_provider_id'=>$physician_provider->id
        ]);


        $program_coordinator_user = User::create([
            'first_name' => "Program",
            'last_name' => "Coordinator",
            'email' => "program_coordinator@clingroup.net",
            'password' => 'password',
            'personal_email' => 'program_coordinator@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $program_coordinator_user->assignRole(RoleEnum::ProgramCoordinator);

        $program_coordinator_provider = ServiceProvider::create([
            'user_id' => $program_coordinator_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::ProgramCoordinator)->first()->id,
            'service_provider_id'=>$program_coordinator_provider->id
        ]);






        $program_coordinator_user = User::create([
            'first_name' => "Hussen",
            'last_name' => "Coordinator",
            'email' => "hprogram_coordinator@clingroup.net",
            'password' => 'password',
            'personal_email' => 'hprogram_coordinator@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $program_coordinator_user->assignRole(RoleEnum::ProgramCoordinator);

        $program_coordinator_provider = ServiceProvider::create([
            'user_id' => $program_coordinator_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::ProgramCoordinator)->first()->id,
            'service_provider_id'=>$program_coordinator_provider->id
        ]);

        $project_manager_user = User::create([
            'first_name' => "Project",
            'last_name' => "Manger",
            'email' => "project_manager@clingroup.net",
            'password' => 'password',
            'personal_email' => 'project_manager@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $project_manager_user->assignRole(RoleEnum::ProjectManager);

        $project_manager_provider = ServiceProvider::create([
            'user_id' => $project_manager_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::ProjectManager)->first()->id,
            'service_provider_id'=>$project_manager_provider->id
        ]);



        $project_manager_user = User::create([
            'first_name' => "Zed",
            'last_name' => "Manger",
            'email' => "zproject_manager@clingroup.net",
            'password' => 'password',
            'personal_email' => 'zproject_manager@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $project_manager_user->assignRole(RoleEnum::ProjectManager);

        $project_manager_provider = ServiceProvider::create([
            'user_id' => $project_manager_user->id,
            'contract_type'=> ContractTypeEnum::Freelancer,
            'contract_rate_price'=> '22.50',
            'city'=> 'City',
            'country_id'=> Country::inRandomOrder()->first()->id,
        ]);

        ServiceProviderType::create([
            'service_type_id'=>ServiceType::where('name',RoleEnum::ProjectManager)->first()->id,
            'service_provider_id'=>$project_manager_provider->id
        ]);


    }
}

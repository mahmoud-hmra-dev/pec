<?php

namespace Database\Seeders;

use App\Models\ServiceProviderType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
			RoleSeeder::class ,
			DocumentTypeSeeder::class ,
			CountrySeeder::class ,
			UserSeeder::class ,

            QuestionTypeSeeder::class ,
            ServiceTypeSeeder::class,
            HospitalSeeder::class ,
            SpecialtySeeder::class ,
            ServiceProviderSeeder::class,
            ClientSeeder::class,
            ProgramSeeder::class,
            SubProgramSeeder::class,
            DoctorSeeder::class ,
            FakeDataSeeder::class ,
            UserPatientSeeder::class ,
            ProgramDrugSeeder::class,
            ProgramCountrySeeder::class,
        ]);
    }
}

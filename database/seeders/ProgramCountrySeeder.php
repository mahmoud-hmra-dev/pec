<?php

namespace Database\Seeders;

use App\Models\Drug;
use App\Models\Hospital;
use App\Models\ProgramCountry;
use App\Models\ProgramDrug;
use Database\Factories\ProgramDrugFactory;
use Database\Factories\s;
use Illuminate\Database\Seeder;

class ProgramCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProgramCountry::factory(20)->create();
    }
}

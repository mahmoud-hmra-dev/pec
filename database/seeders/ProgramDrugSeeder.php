<?php

namespace Database\Seeders;

use App\Models\Drug;
use App\Models\Hospital;
use App\Models\ProgramDrug;
use Database\Factories\ProgramDrugFactory;
use Database\Factories\s;
use Illuminate\Database\Seeder;

class ProgramDrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProgramDrug::factory(20)->create();
    }
}

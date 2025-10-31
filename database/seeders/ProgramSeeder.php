<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Specialty;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Program::factory(5)->create();
    }
}

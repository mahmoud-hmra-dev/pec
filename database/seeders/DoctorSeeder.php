<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('doctors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Doctor::factory(20)->create();
    }
}

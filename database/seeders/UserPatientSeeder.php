<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Client;
use App\Models\Country;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Physician;
use App\Models\Program;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserPatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            if($user->patient){
                $user->birth_of_date = $user->patient->birth_of_date   ;
                $user->save();
            }
        }

    }
}

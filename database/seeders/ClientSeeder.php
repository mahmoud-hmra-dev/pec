<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Client;
use App\Models\Country;
use App\Models\Nurse;
use App\Models\Physician;
use App\Models\Program;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client_user = User::create([
            'first_name' => "client",
            'last_name' => "client",
            'email' => "client@clingroup.net",
            'password' => 'password',
            'personal_email' => 'client@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $client = Client::create([
            'id' => $client_user->id,
            'client_name' => "Client Name",
            'client_address' => "Client Address",
        ]);
        $client_user->assignRole(RoleEnum::CLIENT);

        $client_user = User::create([
            'first_name' => "Ahmad",
            'last_name' => "ERT",
            'email' => "ahmad@clingroup.net",
            'password' => 'password',
            'personal_email' => 'ahmad@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $client = Client::create([
            'id' => $client_user->id,
            'client_name' => "ERT",
            'client_address' => "Feno",
        ]);
        $client_user->assignRole(RoleEnum::CLIENT);
    }
}

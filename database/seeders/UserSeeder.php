<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Client;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => "Clin",
            'last_name' => "Group",
            'email' => "admin@clingroup.net",
            'password' => 'password',
            'personal_email' => 'admin@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $user->assignRole(RoleEnum::ADMIN);

        $user = User::create([
            'first_name' => "Clin",
            'last_name' => "Group",
            'email' => "mhamod@clingroup.net",
            'password' => 'password',
            'personal_email' => 'mhamod@clingroup.net',
            'address' => 'address line 1',
            'city' => 'city name',
            'country_id' => Country::inRandomOrder()->first()->id,
        ]);

        $user->assignRole(RoleEnum::ADMIN);





    }
}

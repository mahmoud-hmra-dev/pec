<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Client;
use App\Models\Country;
use App\Models\Drug;
use App\Models\Nurse;
use App\Models\Pharmacy;
use App\Models\Physician;
use App\Models\Program;
use App\Models\QuestionCategory;
use App\Models\ServiceType;
use App\Models\Specialty;
use App\Models\SubProgram;
use App\Models\User;
use Database\Factories\SubProgramFactory;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = array(
            array("name" => "Project Manager", "code" => "ProgramCoordinator", "is_one" => "1"),
            array("name" => "Nurse", "code" => "Nurse", "is_one" => "1"),
            array("name" => "Physiotherapist", "code" => "Physiotherapist", "is_one" => "1"),
            array("name" => "Psychology", "code" => "Psychology", "is_one" => "1"),
            array("name" => "Nutritionist", "code" => "Nutritionist", "is_one" => "1"),
            array("name" => "Ergo Therapy", "code" => "ErgoTherapy", "is_one" => "1"),
            array("name" => "Speech Therapy", "code" => "SpeechTherapy", "is_one" => "1"),
            array("name" => "PSP Manager", "code" => "PSPManager", "is_one" => "1"),
            array("name" => "Program Coordinator", "code" => "ProgramCoordinator", "is_one" => "1"),
            array("name" => "Safety Coordinator", "code" => "SafetyCoordinator", "is_one" => "1"),
            array("name" => "General Contact", "code" => "GeneralContact", "is_one" => "1"),
            array("name" => "Finance Department", "code" => "FinanceDepartment", "is_one" => "1"),
            array("name" => "Physician", "code" => "Physician", "is_one" => "1"),
        );

        $insert = [];

        foreach ($list as $item) {
            $data = [];
            isset($item['name']) ? $data['name'] = $item['name'] : $data['name'] = null;
            isset($item['code']) ? $data['code'] = $item['code'] : $data['code'] = null;
            isset($item['is_one']) ? $data['is_one'] = $item['is_one'] : $data['is_one'] = null;
            $insert[] = $data;
        }

        ServiceType::insert($insert);
    }
}

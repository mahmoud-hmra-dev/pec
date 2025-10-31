<?php

namespace Database\Seeders;


use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ["name"=>RoleEnum::ADMIN,'guard_name'=>"web"],
            ["name"=>RoleEnum::SUB_ADMIN,'guard_name'=>"web"],
            ["name"=>RoleEnum::NURSE,'guard_name'=>"web"],
            ["name"=>RoleEnum::CLIENT,'guard_name'=>"web"],
            ["name"=>RoleEnum::ProgramCoordinator,'guard_name'=>"web"],
            ["name"=>RoleEnum::ProjectManager,'guard_name'=>"web"],

            ["name"=>RoleEnum::PHYSICIAN,'guard_name'=>"web"],
            ["name"=>RoleEnum::PATIENT,'guard_name'=>"web"],
            ["name"=>RoleEnum::Physiotherapist,'guard_name'=>"web"],
            ["name"=>RoleEnum::Psychology,'guard_name'=>"web"],
            ["name"=>RoleEnum::Nutritionist,'guard_name'=>"web"],
            ["name"=>RoleEnum::ErgoTherapy,'guard_name'=>"web"],
            ["name"=>RoleEnum::SpeechTherapy,'guard_name'=>"web"],
            ["name"=>RoleEnum::PSPManager,'guard_name'=>"web"],
            ["name"=>RoleEnum::SafetyCoordinator,'guard_name'=>"web"],
            ["name"=>RoleEnum::GeneralContact,'guard_name'=>"web"],
            ["name"=>RoleEnum::FinanceDepartment,'guard_name'=>"web"],
        ];

        Role::insert($roles);

        $permissions = [
            ["name"=>PermissionEnum::MANAGE_USERS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_PATIENTS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_CLIENTS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_QUESTIONS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_DOCTORS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_DOCTORS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_ServiceProvider,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_ServiceProvider,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_COUNTRIES,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_HOSPITALS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_PHARMACIES,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_PHARMACIES,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_DOCUMENTS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_PROGRAMS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_SUBPROGRAMS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_Distributors,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_QUESTIONS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_VISITS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_DRUGS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::PatientConsent,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_DRUGS,'guard_name'=>"web"],

            ["name"=>PermissionEnum::VIEW_CLIENTS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_Distributors,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_HOSPITALS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_PATIENTS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_PROGRAMS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_SUBPROGRAMS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_VISITS,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_ShowTimeLine,'guard_name'=>"web"],

            ["name"=>PermissionEnum::VIEW_FOC,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_FOC,'guard_name'=>"web"],
            ["name"=>PermissionEnum::VIEW_FOC_Visits_ShowTimeLine,'guard_name'=>"web"],

            ["name"=>PermissionEnum::VIEW_SafetyReport,'guard_name'=>"web"],
            ["name"=>PermissionEnum::MANAGE_SafetyReport,'guard_name'=>"web"],

        ];

        Permission::insert($permissions);

        $permissions_ids = Permission::all()->pluck('id')->toArray();

        $admin_role = Role::whereName(RoleEnum::ADMIN)->first();
        $admin_role->permissions()->sync($permissions_ids);

        //$patient_role = Role::whereName(RoleEnum::PATIENT)->first();
        //$patient_role->givePermissionTo('manage PatientConsent');


        $program_permissions = [
            PermissionEnum::VIEW_PROGRAMS,
            PermissionEnum::VIEW_SUBPROGRAMS,
            PermissionEnum::VIEW_VISITS,
            PermissionEnum::MANAGE_VISITS,
            PermissionEnum::VIEW_ShowTimeLine,
            PermissionEnum::MANAGE_FOC,
            PermissionEnum::VIEW_FOC,
            PermissionEnum::VIEW_FOC_Visits_ShowTimeLine,
            PermissionEnum::MANAGE_NURSES,
        ];

        foreach (Role::all() as $role) {
            if ($role->name == RoleEnum::NURSE ||
                $role->name == RoleEnum::ProgramCoordinator ||
            $role->name == RoleEnum::SUB_ADMIN) {
                $role->givePermissionTo($program_permissions);
            }
        }

        $role = Role::whereName(RoleEnum::ProjectManager)->first();
        $permissions = [
            PermissionEnum::MANAGE_PROGRAMS,
            PermissionEnum::MANAGE_SUBPROGRAMS,
            PermissionEnum::MANAGE_VISITS,
            PermissionEnum::MANAGE_QUESTIONS,
            PermissionEnum::MANAGE_Distributors,
            PermissionEnum::VIEW_PROGRAMS,
            PermissionEnum::VIEW_SUBPROGRAMS,
            PermissionEnum::VIEW_VISITS,
            PermissionEnum::VIEW_ShowTimeLine,
            PermissionEnum::MANAGE_FOC,
            PermissionEnum::VIEW_FOC,
            PermissionEnum::VIEW_FOC_Visits_ShowTimeLine,
        ];
        $role->givePermissionTo($permissions);

        $client_permissions = [
            PermissionEnum::VIEW_PROGRAMS,
            PermissionEnum::VIEW_SUBPROGRAMS,
        ];

        foreach (Role::all() as $role) {
            if($role->name == RoleEnum::CLIENT){
                $role->givePermissionTo($client_permissions);
            }
        }
    }
}

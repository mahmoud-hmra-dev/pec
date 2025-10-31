<?php

namespace App\Providers;

use App\Enums\ClientContactRoleEnum;
use App\Enums\ContractTypeEnum;
use App\Enums\DocumentTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\DocumentType;
use App\Models\Drug;
use App\Models\HearAbout;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PhpParser\Comment\Doc;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        View::composer('*',function ($view){
            $view->with('countries', Country::orderBy('name')->get());
            $view->with('users', User::all());
            $view->with('genders', [GenderEnum::MALE,GenderEnum::FEMALE,GenderEnum::NOT_TO_SAY]);
            $view->with('roles', [RoleEnum::ADMIN,RoleEnum::SUB_ADMIN]);
            $view->with('contract_types', [ContractTypeEnum::Freelancer,ContractTypeEnum::Employee]);
            $view->with('client_contact_roles', [ClientContactRoleEnum::PSPManager,ClientContactRoleEnum::ProgramCoordinator,ClientContactRoleEnum::SafetyCoordinator,ClientContactRoleEnum::GeneralContact,ClientContactRoleEnum::FinanceDepartment]);
            $view->with('service_types', ServiceType::all());
            $view->with('drugs',  Drug::all());
            $view->with('types', DocumentType::all());

        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}

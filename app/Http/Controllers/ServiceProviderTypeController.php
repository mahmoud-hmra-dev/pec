<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\CertificateRequest;
use App\Http\Requests\ServiceProviderRequest;
use App\Http\Requests\ServiceProviderTypeRequest;
use App\Http\Requests\UserRequest;
use App\Models\Certificate;
use App\Models\CountryServiceProvider;
use App\Models\Program;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use App\Models\SubProgram;
use App\Traits\FileHandler;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ServiceProviderTypeController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_ServiceProvider)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_ServiceProvider)->only('index');
    }

    use FileHandler;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request,$service_provider_id)
    {
        $items = ServiceProviderType::with(['service_provider','service_type','country_services_provider',])->where('service_provider_id',$service_provider_id)->select('service_provider_types.*');
        $sub_programs = SubProgram::all();
        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_ServiceProvider)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                        $actions .= '<a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->addColumn('country_services_provider_text', function ($row) {
                    $resulte = '';
                    foreach ($row->country_services_provider as $item) {
                        !$resulte ? $resulte = 'Sub Program: '.$item->sub_program->name .' - ' .$item->sub_program->drug->name .'- Country: '. $item->country->name
                            : $resulte = $resulte . '<br>Sub Program: '.$item->sub_program->name .' - ' .$item->sub_program->drug->name .'- Country: '. $item->country->name ;
                    }
                    return $resulte;
                })
                ->rawColumns(['action','country_services_provider_text'])
                ->make(true);
        }

        return view('dashboard.service-providers.service_provider_types.index',['service_provider_id'=>$service_provider_id,'sub_programs'=>$sub_programs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(ServiceProviderTypeRequest $request,$service_provider_id)
    {
        $model = ServiceProvider::findOrFail($service_provider_id);

        $serviceTypeId = $request->service_type_id;

        if (!$serviceTypeId && $request->filled('service_type_name')) {
            $serviceType = ServiceType::firstOrCreate(
                ['name' => $request->service_type_name],
                [
                    'code' => Str::upper(Str::slug($request->service_type_name, '_')),
                    'is_one' => false,
                ]
            );

            $serviceTypeId = $serviceType->id;

            Role::firstOrCreate([
                'name' => $serviceType->name,
                'guard_name' => 'web',
            ]);
        }

        $service_provider_type = $model->service_provider_types()->create([
            'service_type_id' => $serviceTypeId,
        ])->load('service_type');

        $user = User::findOrFail($model->user_id);

        $roles = $model->service_types()->pluck('name')->toArray();
        if(!empty($roles)){
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        return response()->json([
            'success' => true,
            'message'=>"Added Successfully",
            'service_type' => $service_provider_type->service_type,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ServiceProviderTypeRequest $request, $service_provider_id, $id)
    {
        $model = ServiceProviderType::findOrFail($id);

        $serviceTypeId = $request->service_type_id;

        if (!$serviceTypeId && $request->filled('service_type_name')) {
            $serviceType = ServiceType::firstOrCreate(
                ['name' => $request->service_type_name],
                [
                    'code' => Str::upper(Str::slug($request->service_type_name, '_')),
                    'is_one' => false,
                ]
            );

            $serviceTypeId = $serviceType->id;

            Role::firstOrCreate([
                'name' => $serviceType->name,
                'guard_name' => 'web',
            ]);
        }

        $model->update([
            'service_type_id' => $serviceTypeId,
        ]);

        $model->load('service_type');


        $ServiceProvider = ServiceProvider::findOrFail($service_provider_id);
        $user = User::findOrFail($ServiceProvider->user_id);

        $roles = $ServiceProvider->service_types()->pluck('name')->toArray();

        if(!empty($roles)){
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }



        return response()->json([
            'success' => true,
            'message'=>"Updated Successfully",
            'service_type' => $model->service_type,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($service_provider_id, $id)
    {
        $model = ServiceProviderType::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }

}

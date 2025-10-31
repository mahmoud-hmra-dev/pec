<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\ServiceTypeRequest;
use App\Models\Hospital;
use App\Models\Physician;
use App\Models\Program;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use App\Models\SubProgram;
use App\Models\User;
use App\Traits\FileHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Patient;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ServiceTypeController extends Controller{

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
    public function index(Request $request)
    {
        $items = ServiceType::select('service_types.*');


        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_ServiceProvider)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                        $actions .= '<a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.service-providers.service_types.index');
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param PatientRequest $request
    * @return   RedirectResponse
    */
    public function store(ServiceTypeRequest $request)
    {
        $model = ServiceType::create([
            'name'=> $request->name,
            'code'=> $request->code,
            'is_one'=> $request->is_one  ? 1:0,
        ]);
        $role = Role::where('name',$request->name)->first();
        if(!$role) {
            $roles = [
                ["name"=>$request->name,'guard_name'=>"web"],
            ];
            Role::insert($roles);
        }
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }


    /**
    * Update the specified resource in storage.
    *
    * @param  PatientRequest $request
    * @param  int  $id
    * @return RedirectResponse
    */
    public function update(ServiceTypeRequest $request, $id)
    {
        $model = ServiceType::find($id);
        $model->update([
            'name'=> $request->name,
            'code'=> $request->code,
            'is_one'=> $request->is_one  ? 1:0,
        ]);
        $role = Role::where('name',$request->name)->first();
        if(!$role) {
            $roles = [
                ["name"=>$request->name,'guard_name'=>"web"],
            ];
            Role::insert($roles);
        }
        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return JsonResponse
    */
    public function destroy($id)
    {
        $model = ServiceType::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}

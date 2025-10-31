<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\CountryServiceProviderRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\UserRequest;
use App\Models\CountryServiceProvider;
use App\Models\FOCVisit;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubProgramServiceProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_ServiceProvider)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_ServiceProvider)->only('index');

    }


    public function index(Request $request , $sub_program_id)
    {
        $service_providers = ServiceProviderType::with(['service_provider','service_provider.user','service_type'])->get();
        $items = CountryServiceProvider::where('sub_program_id',$sub_program_id)->with(['country','service_provider_type','service_provider_type.service_type','service_provider_type.service_provider','service_provider_type.service_provider.user'])->select('country_service_providers.*');


        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_ServiceProvider)) {
                        $actions .= '<a class="destroy_and_replace btn btn-xs btn-dark mr-1" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }
        return view('dashboard.programs.sub_programs.service-providers.index',['sub_program_id'=>$sub_program_id,'service_providers'=>$service_providers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(CountryServiceProviderRequest $request , $sub_program_id)
    {
        $model = SubProgram::findOrFail($sub_program_id);
        $country_service_provider = $model->country_services_provider()
            ->where('service_provider_type_id',$request->service_provider_type_id)
            ->where('country_id',$model->country_id)
            ->count();
        if($country_service_provider >0){
            return response()->json(['success' => true,'message'=>"Service provider type added before!"],200);
        }
        $model->country_services_provider()->create([
            'service_provider_type_id'=>$request->service_provider_type_id,
            'country_id'=>$model->country_id,
        ]);
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PatientRequest $request
     * @param  int  $id
     * @return RedirectResponse
     */
    /*public function update(CountryServiceProviderRequest $request,$sub_program_id, $id)
    {
        $model_deleted = CountryServiceProvider::findOrFail($id);

        $model->update([
            'service_provider_type_id'=>$request->service_provider_type_id,
            'country_id'=>$request->country_id,
        ]);

        return response()->json(['success' => true,'message'=>$request->country_id."Added Successfully".$model->country_id],200);
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy_and_replace(CountryServiceProviderRequest $request, $sub_program_id, $id)
    {
        $model = SubProgram::findOrFail($sub_program_id);
        $model_deleted = CountryServiceProvider::findOrFail($id);
        $model_replaced = CountryServiceProvider::where('sub_program_id', $sub_program_id)
            ->where('service_provider_type_id', $request->service_provider_type_id)
            ->where('country_id', $model->country_id)
            ->first();

        if (!$model_replaced) {
            $model_replaced = $model->country_services_provider()->create([
                'service_provider_type_id' => $request->service_provider_type_id,
                'country_id' => $model->country_id,
            ]);
        }

        $visits = Visit::where('sub_program_id', $sub_program_id)
            ->where('service_provider_type_id', $model_deleted->service_provider_type_id)
            ->where('start_at', null)
            ->get();

        foreach ($visits as $visit) {
            $visit->update([
                'service_provider_type_id' => $model_replaced->service_provider_type_id
            ]);
        }

        $foc_visits = FOCVisit::where('sub_program_id', $sub_program_id)
            ->where('service_provider_type_id', $model_deleted->service_provider_type_id)
            ->where('start_at', null)
            ->get();

        foreach ($foc_visits as $visit) {
            $visit->update([
                'service_provider_type_id' => $model_replaced->service_provider_type_id
            ]);
        }

        $model_deleted->delete();

        return response()->json(['message' => 'The service provider whose information you provided has been replaced with the service provider that was successfully deleted!']);
    }

    /*public function destroy($sub_program_id,$id)
    {
        $model = CountryServiceProvider::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }*/
}

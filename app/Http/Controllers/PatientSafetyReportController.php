<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\HospitalRequest;
use App\Http\Requests\PatientSafetyReportRequest;
use App\Models\CountryServiceProvider;
use App\Models\PatientSafetyReport;
use App\Models\Program;
use App\Models\Service;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Traits\FileHandler;
use Illuminate\Http\Request;
use App\Models\Hospital;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PatientSafetyReportController extends Controller{



    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_SafetyReport)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_SafetyReport)->only('index');

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

        $programs = Program::whereHas('sub_programs.sub_program_patients')->get();
        $sub_programs = SubProgram::with(['sub_program_patients'])->whereHas('sub_program_patients')
            ->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) {
                $service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function ($query) {
                        $query->where('name', RoleEnum::ProgramCoordinator);
                    })
                    ->value('id');

                $query->whereHas('country_services_provider', function ($countryQuery) use ($service_provider_type_id) {
                    $countryQuery->where('service_provider_type_id', $service_provider_type_id);
                });
                return $query;
            })->get();
        $sub_program_patients = SubProgramPatient::with(['patient.user'])
            ->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) {
                $coordinator_service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function ($query) {
                        $query->where('name', RoleEnum::ProgramCoordinator);
                    })
                    ->value('id');
                return $query->whereHas('patient_country_providers', function ($query) use ($coordinator_service_provider_type_id) {
                    $query->whereHas('country_service_provider', function ($query) use ($coordinator_service_provider_type_id) {
                        $query->where('service_provider_type_id', $coordinator_service_provider_type_id);
                    });
                });
            })->get();
        if($request->ajax()){
            $sub_program_patient_id = $request->sub_program_patient_id;
            $sub_program_id = $request->sub_program_id;
            $program_id = $request->program_id;
            $items = PatientSafetyReport::with(['sub_program_patient','sub_program_patient.patient','sub_program_patient.patient.user','sub_program_patient.sub_program','sub_program_patient.sub_program.program'])
                ->when($sub_program_patient_id, function ($query) use ($sub_program_patient_id) {
                    return $query->where('sub_program_patient_id', $sub_program_patient_id);
                })->when($sub_program_id, function ($query) use ($sub_program_id) {
                    return $query->whereHas('sub_program_patient.sub_program', function($query) use ($sub_program_id){
                        $query->where('id', $sub_program_id);
                    });
                })->when($program_id, function ($query) use ($program_id) {
                    return $query->whereHas('sub_program_patient.sub_program', function($query) use ($program_id){
                        $query->where('program_id', $program_id);
                    });
                })->select('patient_safety_reports.*');

            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_SafetyReport)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark mr-1" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }
        return view('dashboard.safety-reports.index',['programs'=>$programs,'sub_program_patients'=>$sub_program_patients,'sub_programs'=>$sub_programs]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param HospitalRequest $request
    * @return JsonResponse
    */
    public function store(PatientSafetyReportRequest $request)
    {

        $file = null;

        if ($request->hasFile('name')) {
            $file =  $this->storeFile($request->file('name'), 'safety-reports', false);
        }
        $model= PatientSafetyReport::create([
            'title'=> $request->title,
            'name'=>$file,
            'description' => $request->description,
            'sub_program_patient_id'=> $request->sub_program_patient_id,
        ]);

        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  HospitalRequest $request
    * @param  int  $id
    * @return JsonResponse
    */
    public function update(PatientSafetyReportRequest $request, $id)
    {
        $model = PatientSafetyReport::findOrFail($id);
        $file = null;

        if ($request->hasFile('name')) {
            $file = $this->updateFile($request->file('name'),$model->name,'safety-reports',false);
        }
        $model->update([
            'title'=> $request->title,
            'name'=>$file ? $file : $model->name,
            'description' => $request->description,
            'sub_program_patient_id'=> $request->sub_program_patient_id,
        ]);
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
        $model = PatientSafetyReport::findOrFail($id);
        $this->deleteFile($model->name);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }

    public function sub_program_patients($sub_program_id)
    {
        $sub_program_patients = SubProgramPatient::where('sub_program_id',$sub_program_id)
            ->with(['patient.user'])
            ->get();
        return response()->json(['sub_program_patients'=>$sub_program_patients],200);
    }
}

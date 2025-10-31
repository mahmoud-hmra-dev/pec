<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\FOCVisitsRequest;
use App\Http\Requests\VisitsRequest;
use App\Models\ActivityType;
use App\Models\FOCVisit;
use App\Models\Question;
use App\Models\QuestionData;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Models\Visit;
use App\Traits\FileHandler;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FOCVisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_FOC)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_FOC)->only('index');

    }
    use FileHandler;
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request , $sub_program_id)
    {
        $sub_program_patients = SubProgramPatient::with(['patient.user'])->where('sub_program_id',$sub_program_id)->whereHas('foc_visits')->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) {
            $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                ->service_provider_types()
                ->whereHas('service_type', function ($query) {
                    $query->where('name', RoleEnum::ProgramCoordinator);
                })
                ->value('id');
            return $query->whereHas('patient_country_providers', function ($query) use ($nurse_service_provider_type_id) {
                $query->whereHas('country_service_provider', function ($query) use ($nurse_service_provider_type_id) {
                    $query->where('service_provider_type_id', $nurse_service_provider_type_id);
                });
            });
        })->get();


        $sub_program = SubProgram::findOrFail($sub_program_id);

        $coordinators = ServiceProviderType::whereHas('service_type', function($query) {
            $query->where('name', RoleEnum::ProgramCoordinator);
        })->get();

        if($request->ajax()){
            $sub_program_patient_id = $request->sub_program_patient_id_filter;
            $site_notified = $request->site_notified;
            $start_at = $request->start_date;
            $finish_at = $request->finish_date;

            $items = FOCVisit::with(['sub_program_patient.patient.user','service_provider_type.service_provider.user','sub_program'])
                ->where('sub_program_id',$sub_program_id)
                ->when($sub_program_patient_id, function ($query) use ($sub_program_patient_id) {
                    return $query->where('sub_program_patient_id', $sub_program_patient_id);
                })
                ->when($site_notified == "Yes" || $site_notified == "No", function ($query) use ($site_notified) {
                    return $query->whereNotNull('site_notified');
                })
                ->when($site_notified == "NULL", function ($query) use ($site_notified) {
                    return $query->whereNull('site_notified');
                })
                ->when($start_at, function ($query) use ($start_at) {
                    $start_at = Carbon::parse($start_at)->subDay();
                    return $query->where(function ($query) use ($start_at) {
                        $query->where('start_at', '>=', $start_at);
                    });
                })
                ->when($finish_at, function ($query) use ($finish_at) {
                    $finish_at = Carbon::parse($finish_at)->addDay();
                    return $query->where(function ($query) use ( $finish_at) {
                        $query->where('start_at', '<=', $finish_at);
                    });
                })
                ->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) {
                    $coordinator_service_provider_type_id = optional(auth()->user()->service_provider)
                        ->service_provider_types()
                        ->whereHas('service_type', function($query){
                            $query->where('name', RoleEnum::ProgramCoordinator);
                        })
                        ->value('id');
                    return $query->where('service_provider_type_id', $coordinator_service_provider_type_id);
                })->select('f_o_c_visits.*');
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_FOC)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.programs.sub_programs.foc_visits.index',['sub_program_patients'=>$sub_program_patients,'sub_program'=>$sub_program ,'coordinators'=>$coordinators]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param VisitsRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(FOCVisitsRequest $request, $sub_program_id,$id)
    {
        $model = FOCVisit::findOrFail($id);
        $model->update($request->validated());
        $attachment = null;

        if ($request->hasFile('attachment')) {
            $attachment =  $this->updateFile($request->file('attachment'),$model->attachment,'FOC',false);
        }
        $model->attachment = $attachment ? $attachment : $model->attachment;
        $model->save();
        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($sub_program_id,$id)
    {
        $model = FOCVisit::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}

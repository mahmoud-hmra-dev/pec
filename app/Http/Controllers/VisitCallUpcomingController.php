<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\VisitScheduleRequest;
use App\Http\Requests\VisitsRequest;
use App\Models\ActivityType;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Physician;
use App\Models\Program;
use App\Models\Question;
use App\Models\QuestionData;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Models\Visit;
use App\Models\VisitSchedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class VisitCallUpcomingController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_VISITS)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_VISITS)->only('index');

    }

    /**
    * Display a listing of the resource.
    *
    * @param Request $request
    * @return Application|Factory|View|JsonResponse
    * @throws Exception
    */
    public function index(Request $request )
    {
        $sub_program_patients = SubProgramPatient::with(['patient.user'])
            ->whereHas('visits')->when(auth()->user()->hasRole(RoleEnum::NURSE), function ($query) {
            $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                ->service_provider_types()
                ->whereHas('service_type', function ($query) {
                    $query->where('name', RoleEnum::NURSE);
                })
                ->value('id');
            return $query->whereHas('patient_country_providers', function ($query) use ($nurse_service_provider_type_id) {
                $query->whereHas('country_service_provider', function ($query) use ($nurse_service_provider_type_id) {
                    $query->where('service_provider_type_id', $nurse_service_provider_type_id);
                });
            });
        })->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) {
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

        $questions = Question::with(['type','sub_program','category','choices'])->get();
        $programs = Program::whereHas('sub_programs.sub_program_patients')->get();
        $sub_programs = SubProgram::with(['sub_program_patients'])->whereHas('sub_program_patients')->get();
        $activity_types = ActivityType::all();

        $nurses = ServiceProviderType::whereHas('service_type', function($query) {
            $query->where('name', 'Nurse');
        })->get();
        if($request->ajax()){
            $sub_program_patient_id = $request->sub_program_patient_id;
            $start_at = $request->start_date;
            $finish_at = $request->finish_date;
            $sub_program_id = $request->sub_program_id;
            $day_week = $request->day_week;
            $program_id = $request->program_id;

            $items = Visit::with(['sub_program_patient.patient.user','service_provider_type.service_provider.user','sub_program','sub_program.program','question_data'])
                ->whereNull('start_at')
                ->when($sub_program_id, function ($query) use ($sub_program_id) {
                    return $query->where('sub_program_id', $sub_program_id);
                })
                ->when($program_id, function ($query) use ($program_id) {
                    return $query->whereHas('sub_program', function($query) use ($program_id){
                        $query->where('program_id', $program_id);
                    });
                })
                ->when($sub_program_patient_id, function ($query) use ($sub_program_patient_id) {
                    return $query->where('sub_program_patient_id', $sub_program_patient_id);
                })
                ->when($start_at, function ($query) use ($start_at) {
                    $start_at = Carbon::parse($start_at)->subDay();
                    return $query->where(function ($query) use ($start_at) {
                        $query->where('should_start_at', '>=', $start_at);
                    });
                })
                ->when($day_week == 'Day', function ($query) {
                    $start_at = now();
                    $start_at_day = $start_at->copy()->startOfDay();
                    return $query->whereDate('should_start_at', $start_at_day);
                })
                ->when($day_week == 'Week', function ($query)  {
                    $start_at = now();
                    $start_of_week = $start_at->copy()->startOfWeek();
                    $end_of_week = $start_at->copy()->endOfWeek();
                    return $query->where('should_start_at', '>=', $start_of_week)
                        ->where('should_start_at', '<=', $end_of_week);
                })
                ->when($finish_at, function ($query) use ($finish_at) {
                    $finish_at = Carbon::parse($finish_at)->addDay();
                    return $query->where(function ($query) use ( $finish_at) {
                        $query->where('should_start_at', '<=', $finish_at);
                    });
                })
                ->when(auth()->user()->hasRole(RoleEnum::NURSE), function ($query) {
                    $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                        ->service_provider_types()
                        ->whereHas('service_type', function($query){
                            $query->where('name', RoleEnum::NURSE);
                        })
                        ->value('id');
                    return $query->where('service_provider_type_id', $nurse_service_provider_type_id);
                })
                ->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) {
                    $coordinator_service_provider_type_id = optional(auth()->user()->service_provider)
                        ->service_provider_types()
                        ->whereHas('service_type', function($query){
                            $query->where('name', RoleEnum::ProgramCoordinator);
                        })
                        ->value('id');
                    return $query->where('service_provider_type_id', $coordinator_service_provider_type_id);
                })
                ->select('visits.*');
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_VISITS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }
        return view('dashboard.visits_calls.upcoming.index',['activity_types'=>$activity_types,'sub_program_patients'=>$sub_program_patients,'sub_programs'=>$sub_programs,'nurses'=>$nurses,'questions'=>$questions,'programs'=>$programs]);
    }
}

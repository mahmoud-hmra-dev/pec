<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\ServiceProviderTypeRequest;
use App\Http\Requests\SubProgramRequest;
use App\Http\Requests\UserRequest;
use App\Models\ActivityType;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\Program;
use App\Models\ProgramCountry;
use App\Models\ProgramDrug;
use App\Models\Question;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_SUBPROGRAMS)->except('index', 'showTimeLine');
        $this->middleware('permission:'.PermissionEnum::VIEW_SUBPROGRAMS)->only('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_ShowTimeLine)->only('showTimeLine');


    }


    public function index(Request $request,$program_id)
    {
        $items = SubProgram::where('program_id',$program_id)->with(['program','country','drug'])
            ->when(auth()->user()->hasRole(RoleEnum::NURSE), function ($query) {
                $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function ($query) {
                        $query->where('name', RoleEnum::NURSE);
                    })
                    ->value('id');

                $query->whereHas('country_services_provider', function ($countryQuery) use ($nurse_service_provider_type_id) {
                        $countryQuery->where('service_provider_type_id', $nurse_service_provider_type_id);
                    });
                return $query;
            })
            ->when(auth()->user()->hasRole(RoleEnum::PHYSICIAN), function ($query) {
                $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function ($query) {
                        $query->where('name', RoleEnum::PHYSICIAN);
                    })
                    ->value('id');

                $query->whereHas('country_services_provider', function ($countryQuery) use ($nurse_service_provider_type_id) {
                    $countryQuery->where('service_provider_type_id', $nurse_service_provider_type_id);
                });
                return $query;
            })
            ->select('sub_programs.*');

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_SUBPROGRAMS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_SUBPROGRAMS)) {
                        $actions .= '<a class="view btn btn-xs btn-success mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> View</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_ServiceProvider)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.service-providers.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i>Service providers</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_PATIENTS)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.patients.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i> Patients</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_QUESTIONS)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.questions.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i> Questions</a>';
                    }

                    if (auth()->user()->can(PermissionEnum::VIEW_ShowTimeLine)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.timeline.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i>Visits Time line</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_VISITS)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.visits.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i>Visits</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_FOC)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.foc.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i>FOC</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_FOC_Visits_ShowTimeLine)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.foc_time_line.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i>FOC Time line</a>';
                    }
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $program_drugs = ProgramDrug::with(['drug',])->where('program_id',$program_id)->get();
        $program_countries = ProgramCountry::with(['country',])->where('program_id',$program_id)->get();
        return view('dashboard.programs.sub_programs.index',['program_id'=>$program_id,'program_drugs'=>$program_drugs,'program_countries'=>$program_countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(SubProgramRequest $request,$program_id)
    {
        $model = Program::findOrFail($program_id);
        $model->sub_programs()->create([
            'name'=> $request->name,
            'country_id'=> $request->country_id,
            'drug_id'=> $request->drug_id,
            'type'=> $request->type,
            'target_number_of_patients'=> $request->target_number_of_patients,
            'eligible'=> $request->eligible ? 1:0,
            'has_calls'=> $request->has_calls? 1:0,
            'has_visits'=> $request->has_visits? 1:0,
            'is_follow_program_date'=> $request->is_follow_program_date? 1:0,
            'start_date'=> $request->start_date,
            'finish_date'=> $request->finish_date,
            'treatment_duration'=> $request->treatment_duration,
            'program_initial'=> $request->program_initial,
            'visit_every_day'=> $request->visit_every_day,
            'call_every_day'=> $request->call_every_day,

            'has_FOC'=> $request->has_FOC? 1:0,
            'cycle_period'=> $request->cycle_period,
            'cycle_number'=> $request->cycle_number,
            'cycle_reminder_at'=> $request->cycle_reminder_at,
        ]);
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(SubProgramRequest $request,$program_id, $id)
    {
        $model = SubProgram::findOrFail($id);
        $model->update([
            'name'=> $request->name,
            'country_id'=> $request->country_id,
            'drug_id'=> $request->drug_id,
            'type'=> $request->type,
            'target_number_of_patients'=> $request->target_number_of_patients,
            'eligible'=> $request->eligible ? 1:0,
            'has_calls'=> $request->has_calls? 1:0,
            'has_visits'=> $request->has_visits? 1:0,
            'is_follow_program_date'=> $request->is_follow_program_date? 1:0,
            'start_date'=> $request->start_date,
            'finish_date'=> $request->finish_date,
            'treatment_duration'=> $request->treatment_duration,
            'program_initial'=> $request->program_initial,
            'visit_every_day'=> $request->visit_every_day,
            'call_every_day'=> $request->call_every_day,

            'has_FOC'=> $request->has_FOC ? 1:0,
            'cycle_period'=> $request->cycle_period,
            'cycle_number'=> $request->cycle_number,
            'cycle_reminder_at'=> $request->cycle_reminder_at,
        ]);


        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($program_id, $id)
    {
        $model = SubProgram::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }





    private function getVisitHistoryByDate($sub_program_id,$start_at_filter, $start_at, $finish_at, $sub_program_patient_id, $type) {
        return Visit::where('sub_program_id', $sub_program_id)
            ->when($sub_program_patient_id, function ($query) use ($sub_program_patient_id) {
                return $query->where('sub_program_patient_id', $sub_program_patient_id);
            })
            ->when(($start_at_filter !=null && $start_at_filter == 1), function ($query) use ($start_at_filter) {
                return $query->whereNotNull('start_at');
            })
            ->when(($start_at_filter !=null && $start_at_filter == 0), function ($query) use ($start_at_filter) {
                return $query->whereNull('start_at');
            })
            ->when($start_at, function ($query) use ($start_at) {
                $start_at = Carbon::parse($start_at)->subDay();
                return $query->where(function ($query) use ($start_at) {
                    $query->where('should_start_at', '>=', $start_at);
                });
            })
            ->when($finish_at, function ($query) use ($finish_at) {
                $finish_at = Carbon::parse($finish_at)->addDay();
                return $query->where(function ($query) use ( $finish_at) {
                    $query->where('should_start_at', '<=', $finish_at);
                });
            })
            ->whereHas('activity_type', function ($query) use ($type) {
                $query->where('name', $type);
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
            ->get();
    }

    /**
     * generate dates of calls and visits base on started at and ended at of a sub program
     *
     * @param null $sub_program_id
     * @return array[]
     */
    public function generatesDates($sub_program_id,$start_at_filter,$start_at,$finish_at,$sub_program_patient_id)
    {
        $sub_program = SubProgram::findOrFail($sub_program_id);

        $visits = [];
        if ($sub_program && $sub_program->has_visits) {
            $end_date = Carbon::parse($sub_program->finish_date);
            if ($sub_program->visit_every_day && $sub_program->visit_every_day > 0) {
                $models = $this->getVisitHistoryByDate($sub_program_id,$start_at_filter,$start_at,$finish_at,$sub_program_patient_id,'Visit');
                foreach ($models as $index => $model) {
                    if($end_date->lessThan($model->should_start_at))
                        return null;
                    $visits[] = [
                        'index' => $index,
                        'group' => 'visit',
                        'content' => 'visit with '.$model->sub_program_patient->patient->user->first_name . ' ' . $model->sub_program_patient->patient->user->last_name,
                        'sub_program_patient_id' => $model->sub_program_patient_id,
                        'model_id' => $model->id,
                        'className' => 'blue',
                        'start' => $model->should_start_at,
                        'sub_program_id' => $model->sub_program_id,
                        'activity_type_id' => $model->activity_type_id,
                        'service_provider_type_id' => $model->service_provider_type_id,
                        'start_at' => $model->start_at,
                        'should_start_at' => $model->should_start_at,
                        'question_data' => $model->question_data,
                        'type_visit'=> $model->type_visit,
                        'meeting'=>$model->meeting,
                    ];
                }
            }
        }
        $calls = [];
        if ($sub_program && $sub_program->has_calls) {
            $end_date = Carbon::parse($sub_program->finish_date);
            if ($sub_program->call_every_day && $sub_program->call_every_day > 0) {
                $models = $this->getVisitHistoryByDate($sub_program_id,$start_at_filter,$start_at,$finish_at,$sub_program_patient_id,'Call');
                foreach ($models as $index => $model) {
                    if($end_date->lessThan($model->should_start_at))
                        return null;
                    $calls[] = [
                        'index' => $index,
                        'group' => 'call',
                        'content' => 'call with '.$model->sub_program_patient->patient->user->first_name . ' ' . $model->sub_program_patient->patient->user->last_name,
                        'patient_id' => $model->patient_id,
                        'model_id' => $model->id,
                        'className' => 'red',
                        'start' => $model->should_start_at,
                        'sub_program_id' => $model->sub_program_id,
                        'activity_type_id' => $model->activity_type_id,
                        'service_provider_type_id' => $model->service_provider_type_id,
                        'start_at' => $model->start_at,
                        'should_start_at' => $model->should_start_at,
                        'question_data' => $model->question_data,
                        'type_visit'=> $model->type_visit,
                        'meeting'=>$model->meeting,
                    ];
                }
            }
        }
        return ['visits' => $visits,'calls'=>$calls,'sub_program'=>$sub_program];
    }

    /**
     * show sub programs by program id
     *
     * @param null $program_id
     * @return Application|Factory|View
     */
    public function showByProgramId($program_id)
    {
        $program = Program::query()->with([
            'client',
            'client.user',
            'manager',
            'sub_programs',
            'sub_programs.drug',
            'sub_programs.country'
        ])->where('id',$program_id)->first();
        if($program){
            $sub_programs = $program->sub_programs;
            return view('dashboard.sub_programs.list',compact(['sub_programs','program']));
        }
        abort(404);
    }

    public function showTimeLine(Request $request, $sub_program_id)
    {
        if($request->ajax()){
            $data = $this->generatesDates($sub_program_id,$request->start_at,$request->start_date,$request->finish_date,$request->sub_program_patient_id);
            return response()->json([
                'visits' => $data['visits'],
                'calls' => $data['calls']
            ]);
        }

        $nurse_service_provider_type_id = null;
        if(auth()->user()->hasRole(RoleEnum::NURSE)) {
            $nurse_service_provider_type_id = auth()->user()->service_provider
                ->service_provider_types()
                ->whereHas('service_type', function($query){
                    $query->where('name', RoleEnum::NURSE);
                })
                ->value('id');
        }

        $coordinator_service_provider_type_id = null;
        if(auth()->user()->hasRole(RoleEnum::ProgramCoordinator)) {
            $coordinator_service_provider_type_id = auth()->user()->service_provider
                ->service_provider_types()
                ->whereHas('service_type', function($query){
                    $query->where('name', RoleEnum::ProgramCoordinator);
                })
                ->value('id');
        }

        $sub_program_patients = SubProgramPatient::with(['patient.user'])->where('sub_program_id',$sub_program_id)->whereHas('visits')->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) use ($coordinator_service_provider_type_id) {
            return $query->whereHas('patient_country_providers', function ($query) use ($coordinator_service_provider_type_id) {
                $query->whereHas('country_service_provider', function ($query) use ($coordinator_service_provider_type_id) {
                    $query->where('service_provider_type_id', $coordinator_service_provider_type_id);
                });
            });
        })->when(auth()->user()->hasRole(RoleEnum::NURSE), function ($query) use ($nurse_service_provider_type_id) {
                return $query->whereHas('patient_country_providers', function ($query) use ($nurse_service_provider_type_id) {
                    $query->whereHas('country_service_provider', function ($query) use ($nurse_service_provider_type_id) {
                        $query->where('service_provider_type_id', $nurse_service_provider_type_id);
                    });
                });
            })
            ->get();

        $sub_program = SubProgram::findOrFail($sub_program_id);
        $questions = Question::with(['type','sub_program','category','choices'])->where('sub_program_id',$sub_program_id)->get();
        $activity_types = ActivityType::all();
        $nurses = ServiceProviderType::whereHas('service_type', function($query) {
            $query->where('name', 'Nurse');
        })->get();
        $sub_programs = SubProgram::all();
        return view('dashboard.sub_programs.timeline',compact(['sub_programs','sub_program','sub_program_patients','activity_types','nurses','questions']));
    }

    public function getByCountryId($country_id)
    {
        $sub_programs = SubProgram::with(['drug'])->where('country_id',$country_id)->get();
        if(count($sub_programs) > 0)
            return response()->json(['sub_programs'=>$sub_programs],200);
        return response()->json(['sub_programs'=>[]],400);
    }
}

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
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FOCVisitTimeLineController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::VIEW_FOC_Visits_ShowTimeLine);

    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request, $sub_program_id)
    {
        if($request->ajax()){
            $data = $this->generatesDates($sub_program_id,$request->start_date,$request->finish_date,$request->sub_program_patient_id);
            return response()->json([
                'foc_visits' => $data['foc_visits'],
            ]);
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

        $sub_program_patients = SubProgramPatient::with(['patient.user'])->where('sub_program_id',$sub_program_id)->whereHas('foc_visits')->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) use ($coordinator_service_provider_type_id) {
            return $query->whereHas('patient_country_providers', function ($query) use ($coordinator_service_provider_type_id) {
                $query->whereHas('country_service_provider', function ($query) use ($coordinator_service_provider_type_id) {
                    $query->where('service_provider_type_id', $coordinator_service_provider_type_id);
                });
            });
        })->get();

        $sub_program = SubProgram::findOrFail($sub_program_id);
        $coordinators = ServiceProviderType::whereHas('service_type', function($query) {
            $query->where('name', RoleEnum::ProgramCoordinator);
        })->get();
        $sub_programs = SubProgram::all();
        return view('dashboard.programs.sub_programs.foc_visits.timeline',compact(['sub_programs','sub_program','sub_program_patients','coordinators']));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param VisitsRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    private function getVisitHistoryByDate($sub_program_id, $start_at, $finish_at, $sub_program_patient_id) {
        return FOCVisit::where('sub_program_id', $sub_program_id)
            ->when($sub_program_patient_id, function ($query) use ($sub_program_patient_id) {
                return $query->where('sub_program_patient_id', $sub_program_patient_id);
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
            })
            ->get();
    }

    /**
     * generate dates of calls and visits base on started at and ended at of a sub program
     *
     * @param null $sub_program_id
     * @return array[]
     */
    public function generatesDates($sub_program_id,$start_at,$finish_at,$sub_program_patient_id)
    {
        $sub_program = SubProgram::findOrFail($sub_program_id);

        $foc_visits = [];
        if ($sub_program && $sub_program->has_visits) {
            $end_date = Carbon::parse($sub_program->finish_date);
            if ($sub_program->visit_every_day && $sub_program->visit_every_day > 0) {
                $models = $this->getVisitHistoryByDate($sub_program_id,$start_at,$finish_at,$sub_program_patient_id);
                foreach ($models as $index => $model) {
                    if($end_date->lessThan($model->start_at))
                        return null;
                    $foc_visits[] = [
                        'index' => $index,
                        'content' => 'FOC visit with '.$model->sub_program_patient->patient->user->first_name . ' ' . $model->sub_program_patient->patient->user->last_name,
                        'sub_program_patient_id' => $model->sub_program_patient_id,
                        'model_id' => $model->id,
                        'className' => 'blue',
                        'sub_program_id' => $model->sub_program_id,
                        'service_provider_type_id' => $model->service_provider_type_id,
                        'start' => $model->start_at,
                        'start_at' => $model->start_at,
                        'site_notified'         => $model->site_notified,
                        'notification_method'         => $model->notification_method,
                        'collected_from_pharmacy'         => $model->collected_from_pharmacy,
                        'warehouse_call'         => $model->warehouse_call,
                        'reminder_at'                 => $model->reminder_at,
                    ];
                }
            }
        }
        return ['foc_visits' => $foc_visits,'sub_program'=>$sub_program];
    }

}

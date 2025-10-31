<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\ServiceProviderTypeRequest;
use App\Http\Requests\SubProgramRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\VisitsRequest;
use App\Models\ActivityType;
use App\Models\Drug;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\PatientDocument;
use App\Models\PatientSafetyReport;
use App\Models\Program;
use App\Models\Question;
use App\Models\QuestionData;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitDocument;
use App\Traits\FileHandler;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PatientVisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_VISITS)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_VISITS)->only('index');
    }

    use FileHandler;
    public function index(Request $request,$sub_program_id,$sub_program_patient_id)
    {
        $patients = Patient::with(['user'])->get();
        $sub_programs = SubProgram::all();
        $activity_types = ActivityType::all();
        $questions = Question::with(['type','sub_program','category','choices'])->where('sub_program_id',$sub_program_id)->get();

        $service_providers = ServiceProviderType::get();
        if($request->ajax()){
            $start_at_filter = $request->start_at;
            $start_at = $request->start_date;
            $finish_at = $request->finish_date;
            $items = Visit::with(['sub_program_patient','sub_program_patient.saftey_reports','sub_program'])->where('sub_program_patient_id',$sub_program_patient_id)
                ->where('sub_program_id',$sub_program_id)
                ->when(($start_at_filter == 1 and $start_at_filter !=null), function ($query) use ($start_at_filter) {
                    return $query->whereNotNull('start_at');
                })
                ->when(($start_at_filter == 0 and $start_at_filter !=null), function ($query) use ($start_at_filter) {
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
                ->with(['sub_program_patient.patient.user','visit_documents','service_provider_type.service_provider.user','sub_program','question_data','sub_program.program.client'])->select('visits.*');

            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_VISITS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark mr-1" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.patients.visits.index',['sub_program_patient_id'=>$sub_program_patient_id,'sub_program_id'=>$sub_program_id,'activity_types'=>$activity_types,'patients'=>$patients,'sub_programs'=>$sub_programs,'service_providers'=>$service_providers,'questions'=>$questions]);
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  PatientRequest $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(VisitsRequest $request,$sub_program_patient_id, $id)
    {
        $model = Visit::findOrFail($id);
        $model->update($request->validated());

        $sub_program_patient = SubProgramPatient::find($model->sub_program_patient_id);

        if(!empty($request->safety_reports)) {
            PatientSafetyReport::where('sub_program_patient_id', $sub_program_patient->id)
                ->whereNotIn('id', array_column($request->safety_reports, 'id'))
                ->delete();

            foreach ($request->safety_reports as $key => $item) {
                if (isset($item['id']) and $request->hasFile('safety_reports.'.$key.'.name')) {
                    $PatientSafetyReport = PatientSafetyReport::where('id',$item['id'])->first();
                    $documentPath = $this->updateFile($request->file('safety_reports.'.$key.'.name'),$PatientSafetyReport->name,'patients/documents',false);

                    $PatientSafetyReport->update([
                        'name'  => $documentPath,
                        'description' => $item['description'],
                        'title' => $item['title'],
                    ]);

                } elseif( isset($item['name'])  and  $item['name'] != null and $request->hasFile('safety_reports.'.$key.'.name')) {
                    $documentPath = $this->storeFile($request->file('safety_reports.'.$key.'.name'), 'patients/documents', false);
                    $sub_program_patient->saftey_reports()->create([
                        'name'  => $documentPath,
                        'description' => $item['description'],
                        'title' => $item['title'],
                    ]);
                }
            }
        } else {
            PatientSafetyReport::where('sub_program_patient_id', $sub_program_patient->id)->delete();
        }

        if(!empty($request->questions)) {
            foreach ($request->questions as $key => $question) {
                $question_data = QuestionData::where('id',$question['id'])->first();
                if($question_data){
                    $question_data->update([
                        'content' =>is_array($question['content']) ? json_encode($question['content']) :  $question['content'],
                    ]);
                } else {
                    $model->question_data()->create([
                        'question_id' => $question['question_id'],
                        'content' =>is_array($question['content']) ? json_encode($question['content']) :  $question['content'],
                    ]);
                }
            }
        }
        if(!empty($request->documents)) {
            VisitDocument::where('visit_id', $model->id)
                ->whereNotIn('id', array_column($request->documents, 'id'))
                ->delete();
            foreach ($request->documents as $key => $item) {
                if (isset($item['id']) and $request->hasFile('documents.'.$key.'.name')) {
                    $model = VisitDocument::where('id',$item['id'])->first();
                    $documentPath = $this->updateFile($request->file('documents.'.$key.'.name'),$model->name,'visits/documents',false);
                    $model->update([
                        'name'  => $documentPath,
                        'description' => $item['description'],
                        'type' => $item['type'],
                    ]);
                } elseif( isset($item['type']) and isset($item['name']) and $item['type'] != null and  $item['name'] != null and $request->hasFile('documents.'.$key.'.name')) {
                    $documentPath = $this->storeFile($request->file('documents.'.$key.'.name'), 'visits/documents', false);
                    $model->visit_documents()->create([
                        'name'  => $documentPath,
                        'description' => $item['description'],
                        'type' => $item['type'],
                    ]);
                }
            }
        } else {
            VisitDocument::where('visit_id', $model->id)->delete();
        }


        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($sub_program_patient_id,$id)
    {
        $model = Visit::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}

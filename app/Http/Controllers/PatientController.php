<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\VisitsRequest;
use App\Models\Hospital;
use App\Models\Physician;
use App\Models\Program;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use App\Models\SubProgram;
use App\Models\User;
use App\Traits\FileHandler;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Patient;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::VIEW_PATIENTS)->only('index');
        $this->middleware('permission:'.PermissionEnum::MANAGE_PATIENTS)->except('index');
    }

    use FileHandler;
    /**
    * Display a listing of the resource.
    *
    * @param Request $request
    * @return Application|Factory|View|JsonResponse
    * @throws Exception
    */
    public function index(Request $request )
    {

        $items = Patient::with(['user','user.country','hospital'])->select('patients.*');
        $hospitals = Hospital::all();


        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_PATIENTS)) {
                        $actions .= '
                            <a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_PATIENTS)) {
                        $actions .= '<a class="view btn btn-xs btn-success mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> View</a>
                            ';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.patients.index',['hospitals'=>$hospitals]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param PatientRequest $request
    * @return   RedirectResponse
    */
    public function store(PatientRequest $request )
    {
        $user = new User();
        $user->fill($request->only(['first_name',
            'last_name',
            'phone',
            'email',
            'password',
            'image',
            'personal_email',
            'country_id',
            'city',
            'address',
        ]));
        $user->save();

        $model = new Patient();
        $model->fill(array_merge($request->only([
            'patient_no',
            'birth_of_date',
            'height',
            'weight',
            'BMI',
            'is_over_weight',
            'comorbidities',
            'gender',
            'is_eligible',
            'is_pregnant',
            'reporter_name',
            'hospital_id',
            'discuss_by',
            'street',
            'city',
            'address',
        ]),['id'=>$user->id]));
        $model->save();
        $user->assignRole(RoleEnum::PATIENT);

        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }


    /*public function visits($patient){
        $sub_program = SubProgram::where('id',$patient->sub_program_id)->first();
        $nurses = ServiceProviderType::with(['service_provider', 'service_type'])
            ->whereHas('service_type', function($query) {
                $query->where('service_types.name', 'Nurse');
            })->inRandomOrder()->firstOrFail();

        if($sub_program && $sub_program->has_visits && $sub_program->treatment_duration){
            $start_date = Carbon::parse($patient->created_at);
            if($sub_program->visit_every_day && $sub_program->visit_every_day > 0){
                $number_of_visits = $sub_program->treatment_duration/$sub_program->visit_every_day ;
                for ($i=0;$i<$number_of_visits ;$i++) {
                    $patient->visits()->create([
                        'sub_program_id'=>$sub_program->id,
                        'activity_type_id'=>1,
                        'service_provider_type_id'=>$nurses ? $nurses->id : null,
                        'should_start_at'=>$start_date,
                    ]);
                    $start_date = $start_date->addDays($sub_program->visit_every_day);
                }
            }
        }
        if($sub_program && $sub_program->has_calls && $sub_program->treatment_duration && $sub_program->call_every_day && $sub_program->call_every_day > 0){
            $start_date = Carbon::parse($patient->created_at);
            $number_of_visits = $sub_program->treatment_duration/$sub_program->call_every_day ;
            for ($i=0;$i<$number_of_visits ;$i++) {
                $patient->visits()->create([
                    'sub_program_id'=>$sub_program->id,
                    'activity_type_id'=>2,
                    'service_provider_type_id'=>$nurses ? $nurses->id : null,
                    'should_start_at'=>$start_date,
                ]);
                $start_date = $start_date->addDays($sub_program->call_every_day);
            }
        }
    }*/

    /**
    * Update the specified resource in storage.
    *
    * @param  PatientRequest $request
    * @param  int  $id
    * @return RedirectResponse
    */
    public function update(PatientRequest $request, $id)
    {
        $user = User::find($id);
        $user->update($request->only(['first_name',
            'last_name',
            'phone',
            'email',
            'password',
            'image',
            'personal_email',
            'country_id',
            'city',
            'address',
        ]));
        $patient = Patient::find($id);
        $patient->update($request->only([
            'patient_no',
            'birth_of_date',
            'height',
            'weight',
            'BMI',
            'is_over_weight',
            'comorbidities',
            'gender',
            'is_eligible',
            'is_pregnant',
            'reporter_name',
            'hospital_id',
            'discuss_by',
            'street',
            'city',
            'address',
        ]));

        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return JsonResponse
    */
    public function destroy($id)
    {
        $model = User::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}

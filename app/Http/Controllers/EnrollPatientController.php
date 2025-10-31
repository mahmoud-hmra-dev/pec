<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\EnrollPatientRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\SubProgramPatientRequest;
use App\Http\Requests\VisitsRequest;
use App\Models\ClientDocument;
use App\Models\Country;
use App\Models\CountryServiceProvider;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\EudraStudy;
use App\Models\Hospital;
use App\Models\PatientDoctor;
use App\Models\PatientDocument;
use App\Models\PatientSafetyReport;
use App\Models\Pharmacy;
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
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class EnrollPatientController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::MANAGE_PATIENTS)->except('index');
        $this->middleware('permission:' . PermissionEnum::VIEW_PATIENTS)->only('index');
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
        $hospitals = Hospital::all();
        $doctors = Doctor::all();
        $sub_programs_drugs = Drug::has('sub_programs')->with('sub_programs')->get();
        $programs = Program::has('sub_programs')->with('sub_programs')->get();
        $sub_programs_countries = Country::has('sub_programs')->with('sub_programs')->get();
        $pharmacies  = Pharmacy::all();

        $sub_programs = SubProgram::with(['program','program.client', 'country', 'drug'])
            ->when($request->query('program_id'), function ($query) use ($request) {
                $program_id = $request->query('program_id');
                return $query->where('program_id', $program_id);
            })
            ->when($request->query('drug_id'), function ($query) use ($request) {
                $drug_id = $request->query('drug_id');
                return $query->where('drug_id', $drug_id);
            })
            ->when($request->query('sub_program_country_id'), function ($query) use ($request) {
                $country_id = $request->query('sub_program_country_id');
                return $query->where('country_id', $country_id);
            })
            ->select('sub_programs.*');

        if ($request->ajax()) {
            return DataTables::eloquent($sub_programs)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_PATIENTS)) {
                        $actions .= '<a class="enroll btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i>Enroll Patient</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_PATIENTS)) {
                        $actions .= '<a class="ml-2 btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.patients.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i> Patients</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_VISITS)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.visits.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i>Visits</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_FOC)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('sub_programs.foc.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i>FOC</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.enroll_patient.index', ['doctors'=>$doctors,'hospitals' => $hospitals, 'sub_programs_drugs' => $sub_programs_drugs, 'programs' => $programs, 'sub_programs_countries' => $sub_programs_countries,'pharmacies'=>$pharmacies]);
    }


    public function nurses($sub_program_id)
    {
        $nurses = CountryServiceProvider::where('sub_program_id',$sub_program_id)
            ->with(['service_provider_type','service_provider_type.service_type','service_provider_type.service_provider.user'])
            ->whereHas('service_provider_type.service_type', function($query) {
                $query->where('name', 'Nurse');
            })->get();
        return response()->json(['nurses'=>$nurses],200);
    }

    public function physicians($sub_program_id)
    {
        $physicians = CountryServiceProvider::where('sub_program_id',$sub_program_id)
            ->with(['service_provider_type','service_provider_type.service_type','service_provider_type.service_provider.user'])
            ->whereHas('service_provider_type.service_type', function($query) {
                $query->where('name', 'Physician');
            })->get();
        return response()->json(['physicians'=>$physicians],200);
    }

    public function coordinators($sub_program_id)
    {
        $coordinators = CountryServiceProvider::where('sub_program_id',$sub_program_id)
            ->with(['service_provider_type','service_provider_type.service_type','service_provider_type.service_provider.user'])
            ->whereHas('service_provider_type.service_type', function($query) {
                $query->where('name', 'Program Coordinator');
            })->get();

        return response()->json(['coordinators'=>$coordinators],200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param PatientRequest $request
     * @return   RedirectResponse
     */
    public function update(EnrollPatientRequest $request, $id)
    {
        if($request->email){
            $user = User::where(function ($query) use ($request) {
                $query->whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
                    ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
                    ->where('birth_of_date', $request->birth_of_date);
            })
                ->where('email', $request->email)
                ->first();
        } else {
            $user = User::where(function ($query) use ($request) {
                $query->whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
                    ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
                    ->where('birth_of_date', $request->birth_of_date);
            })
                ->first();
        }


        if (!$user) {
            $user = new User();
            $user->fill($request->only(['first_name',
                'last_name',
                'phone',
                'email',
                'password',
                'birth_of_date',
                'country_id',
                'city',
                'street',
                'address',
            ]));
            $user->save();
        } else {
            $user->update(
                $request->only([
                    'first_name',
                    'last_name',
                    'phone',
                    'email',
                    'birth_of_date',
                    'country_id',
                    'city',
                    'address',
                    'street',
                ])
            );
        }


        $sub_program = SubProgram::find($id);
        $end_date = Carbon::parse($sub_program->finish_date);
        if ($end_date->lessThan(now())) {
            return response()->json(['success' => false, 'message' => "Sub program is terminated!"], 400);
        }

        $model = Patient::find($user->id);

        if (!$model) {
            $model = new Patient();
            $model->fill(array_merge(
                $request->only([
                    'patient_no',
                    'birth_of_date',
                    'height',
                    'weight',
                    'BMI',
                    'is_over_weight',
                    'comorbidities',
                    'gender',
                    'is_eligible',
                    'pregnant',
                    'reporter_name',
                    'hospital_id',
                    'discuss_by',
                    'street',
                    'city',
                    'address',
                    'mc_chronic_diseases','mc_medications','mc_surgeries','fmc_chronic_diseases','is_not_eligible'
                ]),
                ['id' => $user->id]
            ));

            $user->assignRole(RoleEnum::PATIENT);
            if ($request->hasFile('is_eligible_document')) {
                $model->is_eligible_document =  $this->storeFile($request->file('is_eligible_document'), 'patients/documents', false);
            }
            $model->save();

            if($request->is_eligible == 'on') {
                if(!empty($request->documents) ){
                    foreach ($request->documents as $key => $item) {
                        if ($request->hasFile('documents.'.$key.'.name')) {
                            $documentPath = $this->storeFile($request->file('documents.'.$key.'.name'), 'patients/documents', false);
                            $model->patient_documents()->create([
                                'name'  => $documentPath,
                                'description' => $item['description'],
                                'type' => $item['type'],
                            ]);
                        }
                    }
                }





                $sub_program_patient = $model->sub_program_patients()->create([
                    'sub_program_id' => $id,
                    'is_consents'=> $request->is_consents,
                    'is_their_safety_report'=> $request->is_their_safety_report,
                ]);
                if ($request->hasFile('consent_document')) {
                    $sub_program_patient->consent_document =  $this->storeFile($request->file('consent_document'), 'patients/documents', false);
                }

                $sub_program_patient->save();

                if(!empty($request->safety_reports) ){
                    foreach ($request->safety_reports as $key => $item) {
                        if ($request->hasFile('safety_reports.'.$key.'.name')) {
                            $documentPath = $this->storeFile($request->file('safety_reports.'.$key.'.name'), 'patients/documents', false);
                            $sub_program_patient->saftey_reports()->create([
                                'name'  => $documentPath,
                                'description' => $item['description'],
                                'title' => $item['title'],
                            ]);
                        }
                    }
                }

                $nurse = null;
                if($request->nurse){
                    $nurse = $sub_program_patient->patient_country_providers()->create([
                        'country_service_provider_id' => $request->nurse,
                    ]);
                }

                $coordinator = null;
                if($request->coordinator){
                    $coordinator = $sub_program_patient->patient_country_providers()->create([
                        'country_service_provider_id' => $request->coordinator,
                    ]);
                }

                /*$physician = $sub_program_patient->patient_country_providers()->create([
                'country_service_provider_id' => $request->physician,
                    ]);*/
                if($request->doctor_id){
                    $doctor = $model->patient_doctors()->create([
                        'isActive'=> 1,
                        'doctor_id'=> $request->doctor_id,
                    ]);
                }

                $this->visits($sub_program_patient, $nurse , $coordinator);
                $this->foc_visits($sub_program_patient,$coordinator);
                return response()->json(['success' => true, 'message' => "Enrolled Successfully"], 200);

            } else {
                return response()->json(['success' => true, 'message' => "Added Successfully info patient but do no enrolled!"], 200);
            }

        } else {
            $model->update($request->only([
                'patient_no',
                'birth_of_date',
                'height',
                'weight',
                'BMI',
                'is_over_weight',
                'comorbidities',
                'gender',
                'is_eligible',
                'pregnant',
                'reporter_name',
                'hospital_id',
                'pharmacy_id',
                'discuss_by',
                'street',
                'city',
                'address',
                'mc_chronic_diseases','mc_medications','mc_surgeries','fmc_chronic_diseases','is_not_eligible'
            ]));
            if($request->is_eligible == 'on') {
                if ($request->hasFile('is_eligible_document')) {
                    $model->is_eligible_document = $this->updateFile($request->file('is_eligible_document'),$model->is_eligible_document,'patients/documents',false);
                }
                $model->save();
                if(!empty($request->documents)) {
                    PatientDocument::where('patient_id', $model->id)
                        ->whereNotIn('id', array_column($request->documents, 'id'))
                        ->delete();
                    foreach ($request->documents as $key => $item) {
                        if (isset($item['id']) and $request->hasFile('documents.'.$key.'.name')) {
                            $model = PatientDocument::where('id',$item['id'])->first();
                            $documentPath = $this->updateFile($request->file('documents.'.$key.'.name'),$model->name,'patients/documents',false);
                            $model->update([
                                'name'  => $documentPath,
                                'description' => $item['description'],
                                'type' => $item['type'],
                            ]);
                        } elseif( isset($item['type']) and isset($item['name']) and $item['type'] != null and  $item['name'] != null and $request->hasFile('documents.'.$key.'.name')) {
                            $documentPath = $this->storeFile($request->file('documents.'.$key.'.name'), 'patients/documents', false);
                            $model->patient_documents()->create([
                                'name'  => $documentPath,
                                'description' => $item['description'],
                                'type' => $item['type'],
                            ]);
                        }
                    }
                } else {
                    PatientDocument::where('patient_id', $model->id)->delete();
                }
                $doctor = $model->patient_doctors()->where('doctor_id',$request->doctor_id)->first();
                if(!$doctor){
                    $model->patient_doctors()->update(['isActive'=> 0,]);
                    $doctor = $model->patient_doctors()->create([
                        'isActive'=> 1,
                        'doctor_id'=> $request->doctor_id,
                    ]);
                }
                $sub_program_patient = $model->sub_program_patients()->first();
                if (!$sub_program_patient) {
                    $sub_program_patient = $model->sub_program_patients()->create([
                        'sub_program_id' => $id,
                        'is_consents'=> $request->is_consents,
                        'is_their_safety_report'=> $request->is_their_safety_report,
                    ]);
                    if ($request->hasFile('consent_document')) {
                        $sub_program_patient->consent_document =  $this->storeFile($request->file('consent_document'), 'patients/documents', false);
                    }
                    $sub_program_patient->save();


                    if(!empty($request->safety_reports)) {
                        PatientSafetyReport::where('sub_program_patient_id', $sub_program_patient->id)
                            ->whereNotIn('id', array_column($request->safety_reports, 'id'))
                            ->delete();

                        foreach ($request->safety_reports as $key => $item) {
                            if (isset($item['id']) and $request->hasFile('safety_reports.'.$key.'.name')) {
                                $model = PatientSafetyReport::where('id',$item['id'])->first();
                                $documentPath = $this->updateFile($request->file('safety_reports.'.$key.'.name'),$model->name,'patients/documents',false);

                                $model->update([
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

                    $nurse = null;
                    if($request->nurse){
                        $nurse = $sub_program_patient->patient_country_providers()->create([
                            'country_service_provider_id' => $request->nurse,
                        ]);
                    }
                    $coordinator = null;
                    if($request->coordinator){
                        $coordinator = $sub_program_patient->patient_country_providers()->create([
                            'country_service_provider_id' => $request->coordinator,
                        ]);
                    }

                    /*$physician = $sub_program_patient->patient_country_providers()->create([
                        'country_service_provider_id' => $request->physician,
                    ]);*/

                    $this->visits($sub_program_patient, $nurse , $coordinator);
                    $this->foc_visits($sub_program_patient,$coordinator);
                    return response()->json(['success' => true, 'message' => "Added Successfully"], 200);
                } else {
                    $old_sub_program = true;
                    foreach ($model->sub_program_patients as $patient) {
                        $end_date = Carbon::parse($patient->sub_program->finish_date);
                        if ($end_date->greaterThan(now())) {
                            $old_sub_program = false;
                            break;
                        }
                    }

                    if ($old_sub_program) {

                        $sub_program_patient = $model->sub_program_patients()->create([
                            'sub_program_id' => $id,
                            'is_consents'=> $request->is_consents,
                            'is_their_safety_report'=> $request->is_their_safety_report,
                        ]);
                        if ($request->hasFile('consent_document')) {
                            $sub_program_patient->consent_document =  $this->storeFile($request->file('consent_document'), 'patients/documents', false);
                        }
                        $sub_program_patient->save();

                        $nurse = null;
                        if($request->nurse){
                            $nurse = $sub_program_patient->patient_country_providers()->create([
                                'country_service_provider_id' => $request->nurse,
                            ]);
                        }
                        $coordinator = null;
                        if($request->coordinator){
                            $coordinator = $sub_program_patient->patient_country_providers()->create([
                                'country_service_provider_id' => $request->coordinator,
                            ]);
                        }


                        if(!empty($request->safety_reports)) {
                            PatientSafetyReport::where('sub_program_patient_id', $sub_program_patient->id)
                                ->whereNotIn('id', array_column($request->safety_reports, 'id'))
                                ->delete();

                            foreach ($request->safety_reports as $key => $item) {
                                if (isset($item['id']) and $request->hasFile('safety_reports.'.$key.'.name')) {
                                    $model = PatientSafetyReport::where('id',$item['id'])->first();
                                    $documentPath = $this->updateFile($request->file('safety_reports.'.$key.'.name'),$model->name,'patients/documents',false);

                                    $model->update([
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

                        /*$physician = $sub_program_patient->patient_country_providers()->create([
                            'country_service_provider_id' => $request->physician,
                        ]);*/

                        $this->visits($sub_program_patient, $nurse , $coordinator);
                        $this->foc_visits($sub_program_patient,$coordinator);
                        return response()->json(['success' => true, 'message' => "Added Successfully"], 200);
                    } else {
                        return response()->json(['success' => false, 'message' => "Patient is enrolled in another sub program!"], 400);
                    }
                }
            } else {
                return response()->json(['success' => true, 'message' => "Added Successfully info patient but do no enrolled!"], 200);
            }
        }
    }



    public function visits($sub_program_patient , $nurse , $coordinator){
        $sub_program = SubProgram::where('id',$sub_program_patient->sub_program_id)->first();

        if($sub_program && $sub_program->has_visits && $sub_program->treatment_duration && $sub_program->visit_every_day && $sub_program->visit_every_day > 0){
            $start_date = Carbon::parse($sub_program_patient->created_at);
            if($sub_program->visit_every_day && $sub_program->visit_every_day > 0){
                $number_of_visits = $sub_program->treatment_duration/$sub_program->visit_every_day ;
                for ($i=0;$i<$number_of_visits ;$i++) {
                    $sub_program_patient->visits()->create([
                        'sub_program_id'=>$sub_program->id,
                        'activity_type_id'=>1,
                        'service_provider_type_id'=> $nurse ? $nurse->country_service_provider->service_provider_type_id : null,
                        'should_start_at'=>$start_date,
                    ]);
                    $start_date = $start_date->addDays($sub_program->visit_every_day);
                }
            }
        }

        if($sub_program && $sub_program->has_calls && $sub_program->treatment_duration && $sub_program->call_every_day && $sub_program->call_every_day > 0){
            $start_date = Carbon::parse($sub_program_patient->created_at);
            $number_of_visits = $sub_program->treatment_duration/$sub_program->call_every_day ;
            for ($i=0;$i<$number_of_visits ;$i++) {
                $sub_program_patient->visits()->create([
                    'sub_program_id'=>$sub_program->id,
                    'activity_type_id'=>2,
                    'service_provider_type_id'=>$coordinator ? $coordinator->country_service_provider->service_provider_type_id : null,
                    'should_start_at'=>$start_date,
                ]);
                $start_date = $start_date->addDays($sub_program->call_every_day);
            }
        }
    }

    public function foc_visits($sub_program_patient , $coordinator){
        $sub_program = SubProgram::where('id',$sub_program_patient->sub_program_id)->first();

        if($sub_program && $sub_program->has_FOC && $sub_program->cycle_period > 0 && $sub_program->cycle_number > 0 && $sub_program->call_every_day > 0){
            $start_date = Carbon::parse($sub_program_patient->created_at);
            $reminder_at = Carbon::parse($sub_program_patient->created_at)->subDays($sub_program->cycle_reminder_at);
            $number_of_visits = $sub_program->cycle_number ;
            for ($i=0;$i<$number_of_visits ;$i++) {
                $sub_program_patient->foc_visits()->create([
                    'sub_program_id'=>$sub_program->id,
                    'service_provider_type_id'=>$coordinator ? $coordinator->country_service_provider->service_provider_type_id : null,
                    'start_at'=>$start_date,
                    'reminder_at'=>$reminder_at,
                ]);
                $start_date = $start_date->addDays($sub_program->cycle_period);
                $reminder_at = $reminder_at->addDays($sub_program->cycle_period);
            }
        }
    }


    public function generate_patient_no(Request $request , $country_id)
    {
        $patient = Patient::find($request->patient_id);
        if($patient && ($country_id == $patient->user->country_id)) {
            return response()->json(['patient_no'=>$patient->patient_no],200);
        }

        $country = Country::find($country_id);
        $program_initial = $request->program_initial;
        $map_id = $request->map_id;
        //$program_code = Str::upper(substr($program_name, 0, 4));

        $patient_code = Patient::orderByDesc('id')->first();
        if($patient_code){
            $patient_code = $patient_code->id + 10000;
        } else {
            $patient_code = 10000;
        }

        $patient_no = $program_initial ? $program_initial.'_'.$patient_code.'_'.$country->code.'_'.$map_id: $patient_code.'_'.$country->code.'_'.$map_id;

        return response()->json(['patient_no'=>$patient_no],200);
    }

}

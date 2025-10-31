<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\SubProgramPatientRequest;
use App\Http\Requests\ServiceProviderTypeRequest;
use App\Http\Requests\SubProgramRequest;
use App\Http\Requests\UserRequest;
use App\Models\CountryServiceProvider;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\FOCVisit;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\PatientCountryProvider;
use App\Models\PatientDocument;
use App\Models\PatientSafetyReport;
use App\Models\Pharmacy;
use App\Models\Program;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Models\User;
use App\Models\Visit;
use App\Traits\FileHandler;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubProgramPatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_PATIENTS)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_PATIENTS)->only('index');
    }

    use FileHandler;

    public function index(Request $request , $sub_program_id)
    {
        $items = SubProgramPatient::with(['patient.patient_documents','saftey_reports','patient.patient_doctors','patient.patient_doctors.doctor','sub_program','sub_program.program.client','patient.user','patient_country_providers','patient_country_providers.country_service_provider','patient_country_providers.country_service_provider.service_provider_type.service_type','patient.hospital','patient.pharmacy','patient.user.country'])
            ->where('sub_program_id', $sub_program_id)
            ->when(auth()->user()->hasRole(RoleEnum::NURSE), function ($query) {
                $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function ($query) {
                        $query->where('name', RoleEnum::NURSE);
                    })
                    ->value('id');

                $query->whereHas('patient_country_providers', function ($patientQuery) use ($nurse_service_provider_type_id) {
                    $patientQuery->whereHas('country_service_provider', function ($countryQuery) use ($nurse_service_provider_type_id) {
                        $countryQuery->where('service_provider_type_id', $nurse_service_provider_type_id);
                    });
                });
                return $query;
            })
            ->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) {
                $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function ($query) {
                        $query->where('name', RoleEnum::ProgramCoordinator);
                    })
                    ->value('id');

                $query->whereHas('patient_country_providers', function ($patientQuery) use ($nurse_service_provider_type_id) {
                    $patientQuery->whereHas('country_service_provider', function ($countryQuery) use ($nurse_service_provider_type_id) {
                        $countryQuery->where('service_provider_type_id', $nurse_service_provider_type_id);
                    });
                });
                return $query;
            })
            ->select('sub_program_patients.*');

        $physicians = CountryServiceProvider::where('sub_program_id',$sub_program_id)
            ->with(['service_provider_type','service_provider_type.service_type','service_provider_type.service_provider.user'])
            ->whereHas('service_provider_type.service_type', function($query) {
            $query->where('name', 'Physician');
        })->get();

        $nurses = CountryServiceProvider::where('sub_program_id',$sub_program_id)
            ->with(['service_provider_type','service_provider_type.service_type','service_provider_type.service_provider.user'])
            ->whereHas('service_provider_type.service_type', function($query) {
                $query->where('name', 'Nurse');
            })->get();
        $coordinators = CountryServiceProvider::where('sub_program_id',$sub_program_id)
            ->with(['service_provider_type','service_provider_type.service_type','service_provider_type.service_provider.user'])
            ->whereHas('service_provider_type.service_type', function($query) {
                $query->where('name', 'Program Coordinator');
            })->get();

        $patients = Patient::all();

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) use ($sub_program_id) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_PATIENTS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_PATIENTS)) {
                        $actions .= '<a class="view btn btn-xs btn-success mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> View</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_VISITS)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'. route('sub_programs.patients.visits.index', ['sub_program_id' => $sub_program_id, 'sub_program_patient_id' => $item->id]).'"><i class="mdi mdi-tooltip-edit"></i> visits</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }
        $hospitals = Hospital::all();
        $doctors = Doctor::all();
        $pharmacies = Pharmacy::all();

        return view('dashboard.programs.sub_programs.patients.index',['sub_program_id'=>$sub_program_id,'physicians'=>$physicians,'nurses'=>$nurses,'patients'=>$patients , 'coordinators' => $coordinators , 'hospitals'=>$hospitals,'doctors'=>$doctors,'pharmacies'=>$pharmacies]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(SubProgramPatientRequest $request , $sub_program_id)
    {
        $patient = Patient::find($request->patient_id);
        $sub_program = SubProgram::find($sub_program_id);
        $end_date = Carbon::parse($sub_program->finish_date);
        if ($end_date->lessThan(now())) {
            return response()->json(['success' => true, 'message' => "Sub program is terminated!"], 200);
        }
        $sub_program_patient = $patient->sub_program_patients()->first();
        if (!$sub_program_patient) {
            $sub_program_patient = $patient->sub_program_patients()->create([
                'sub_program_id'=>$sub_program_id,
            ]);
            $nurse = $sub_program_patient->patient_country_providers()->create([
                'country_service_provider_id'=>$request->nurse,
            ]);
            /*$physician = $sub_program_patient->patient_country_providers()->create([
                'country_service_provider_id'=>$request->physician,
            ]);*/
            $coordinator = $sub_program_patient->patient_country_providers()->create([
                'country_service_provider_id' => $request->coordinator,
            ]);

            $this->visits($sub_program_patient,$nurse,$coordinator);
            $this->foc_visits($sub_program_patient,$coordinator);
            return response()->json(['success' => true, 'message' => "Added Successfully"], 200);
        } else {
            $old_sub_program = true;
            foreach ($patient->sub_program_patients as $patient) {
                $end_date = Carbon::parse($patient->sub_program->finish_date);
                if ($end_date->greaterThan(now())) {
                    $old_sub_program = false;
                    break;
                }
            }

            if ($old_sub_program) {
                $sub_program_patient = $patient->sub_program_patients()->create([
                    'sub_program_id'=>$sub_program_id,
                ]);
                $nurse = $sub_program_patient->patient_country_providers()->create([
                    'country_service_provider_id'=>$request->nurse,
                ]);
                $coordinator = $sub_program_patient->patient_country_providers()->create([
                    'country_service_provider_id' => $request->coordinator,
                ]);
                $this->foc_visits($sub_program_patient,$coordinator);
                $this->visits($sub_program_patient,$nurse,$coordinator);
                return response()->json(['success' => true, 'message' => "Added Successfully"], 200);
            } else {
                return response()->json(['success' => true, 'message' => "Patient is enrolled in another sub program!"], 200);
            }
        }

    }
    public function visits($sub_program_patient , $nurse , $coordinator){
        $sub_program = SubProgram::where('id',$sub_program_patient->sub_program_id)->first();
        if($sub_program_patient->visits->count() > 0) {
            if($coordinator){
                $calls = Visit::where('sub_program_id', $sub_program_patient->sub_program_id)
                    ->where('service_provider_type_id', $coordinator->service_provider_type_id)
                    ->where('start_at', null)
                    ->get();

                foreach ($calls as $call) {
                    $call->update([
                        'service_provider_type_id' => $coordinator->service_provider_type_id
                    ]);
                }
            }
            if($nurse) {
                $visits = Visit::where('sub_program_id', $sub_program_patient->sub_program_id)
                    ->where('service_provider_type_id', $nurse->service_provider_type_id)
                    ->where('start_at', null)
                    ->get();

                foreach ($visits as $visit) {
                    $visit->update([
                        'service_provider_type_id' => $nurse->service_provider_type_id
                    ]);
                }
            }


        } else {
            if($nurse && $sub_program && $sub_program->has_visits && $sub_program->treatment_duration && $sub_program->visit_every_day && $sub_program->visit_every_day > 0){
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
            if($coordinator && $sub_program && $sub_program->has_calls && $sub_program->treatment_duration && $sub_program->call_every_day && $sub_program->call_every_day > 0){
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

    }
    public function foc_visits($sub_program_patient , $coordinator){
        $sub_program = SubProgram::where('id',$sub_program_patient->sub_program_id)->first();
        if($coordinator && $sub_program_patient->foc_visits->count() > 0) {
            $foc_visits = FOCVisit::where('sub_program_id', $sub_program_patient->sub_program_id)
                ->where('service_provider_type_id', $coordinator->service_provider_type_id)
                ->where('start_at', null)
                ->get();

            foreach ($foc_visits as $visit) {
                $visit->update([
                    'service_provider_type_id' => $coordinator->service_provider_type_id
                ]);
            }
        } else {
            if($coordinator && $sub_program && $sub_program->has_FOC && $sub_program->cycle_period > 0 && $sub_program->cycle_number > 0 && $sub_program->call_every_day > 0){
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
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  PatientRequest $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(SubProgramPatientRequest $request,$sub_program_id, $id)
    {

        $user = User::find($request->user_id);
        $user->update($request->only(['first_name',
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

        $patient = Patient::find($request->patient_id);
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
        if ($request->hasFile('is_eligible_document')) {
            $patient->is_eligible_document = $this->updateFile($request->file('is_eligible_document'),$patient->is_eligible_document,'patients/documents',false);
        }
        $patient->save();

        if(!empty($request->documents)) {
            PatientDocument::where('patient_id', $patient->id)
                ->whereNotIn('id', array_column($request->documents, 'id'))
                ->delete();
            foreach ($request->documents as $key => $item) {
                if (isset($item['id']) and $request->hasFile('documents.'.$key.'.name')) {
                    $PatientDocument = PatientDocument::where('id',$item['id'])->first();
                    $documentPath = $this->updateFile($request->file('documents.'.$key.'.name'),$PatientDocument->name,'patients/documents',false);
                    $PatientDocument->update([
                        'name'  => $documentPath,
                        'description' => $item['description'],
                        'type' => $item['type'],
                    ]);
                } elseif( isset($item['type']) and isset($item['name']) and $item['type'] != null and  $item['name'] != null and $request->hasFile('documents.'.$key.'.name')) {
                    $documentPath = $this->storeFile($request->file('documents.'.$key.'.name'), 'patients/documents', false);
                    $patient->patient_documents()->create([
                        'name'  => $documentPath,
                        'description' => $item['description'],
                        'type' => $item['type'],
                    ]);
                }
            }
        } else {
            PatientDocument::where('patient_id', $patient->id)->delete();
        }

        $doctor = $patient->patient_doctors()->where('doctor_id',$request->doctor_id)->first();
        if(!$doctor){
            $patient->patient_doctors()->update(['isActive'=> 0,]);
            $doctor = $patient->patient_doctors()->create([
                'isActive'=> 1,
                'doctor_id'=> $request->doctor_id,
            ]);
        }

        $sub_program_patient = SubProgramPatient::find($id);
        $sub_program_patient->update([
            'is_consents'=> $request->is_consents,
            'is_their_safety_report'=> $request->is_their_safety_report,
        ]);

        if ($request->hasFile('consent_document')) {
            $sub_program_patient->consent_document = $this->updateFile($request->file('consent_document'),$sub_program_patient->is_eligible_document,'patients/documents',false);
        }
        $sub_program_patient->save();

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


        $coordinator = false;
        $nurse = false;
        foreach ($sub_program_patient->patient_country_providers as $patient_country_provider ){
            if($patient_country_provider->country_service_provider && $patient_country_provider->country_service_provider->service_provider_type && $patient_country_provider->country_service_provider->service_provider_type->service_type->name === 'Nurse'){
                $nurse = true;
                if($request->nurse){
                    $patient_country_provider->update([
                        'country_service_provider_id'=>$request->nurse,
                    ]);
                }

                $patient_country_provider = PatientCountryProvider::findOrFail($patient_country_provider->id);

                $visits = Visit::where('sub_program_id', $sub_program_id)
                    ->where('sub_program_patient_id', $sub_program_patient->id)
                    ->where('start_at', null)
                    ->where('activity_type_id', 1)
                    ->get();
                if ($visits->count() > 0) {
                    foreach ($visits as $visit) {
                        $visit->update([
                            'service_provider_type_id' => $patient_country_provider->country_service_provider->service_provider_type_id
                        ]);
                    }
                }

            }
            if($patient_country_provider->country_service_provider && $patient_country_provider->country_service_provider->service_provider_type && $patient_country_provider->country_service_provider->service_provider_type->service_type->name === 'Program Coordinator'){
                $coordinator = true;
                if($request->coordinator){
                    $patient_country_provider->update([
                        'country_service_provider_id'=>$request->coordinator,
                    ]);
                }

                $patient_country_provider = PatientCountryProvider::findOrFail($patient_country_provider->id);
                $visits = Visit::where('sub_program_id', $sub_program_id)
                    ->where('sub_program_patient_id', $sub_program_patient->id)
                    ->where('activity_type_id', 2)
                    ->where('start_at', null)
                    ->get();

                foreach ($visits as $visit) {
                    $visit->update([
                        'service_provider_type_id' => $patient_country_provider->country_service_provider->service_provider_type_id
                    ]);
                }

                $foc_visits = FOCVisit::where('sub_program_id', $sub_program_id)
                    ->where('sub_program_patient_id', $sub_program_patient->id)
                    ->get();
                foreach ($foc_visits as $visit) {
                    $visit->update([
                        'service_provider_type_id' => $patient_country_provider->country_service_provider->service_provider_type_id
                    ]);
                }
            }

        }
        if(!$nurse && $request->nurse){
            $nurse = $sub_program_patient->patient_country_providers()->create([
                'country_service_provider_id' => $request->nurse,
            ]);
            $this->visits($sub_program_patient, $nurse , null);

        }

        if(!$coordinator && $request->coordinator){
            $coordinator = $sub_program_patient->patient_country_providers()->create([
                'country_service_provider_id' => $request->coordinator,
            ]);
            $this->visits($sub_program_patient, null , $coordinator);
            $this->foc_visits($sub_program_patient,$coordinator);
        }


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
        $model = SubProgramPatient::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }



    public function get_sub_programs_by_patient_id($patient_id)
    {
        $sub_programs = SubProgram::whereHas('sub_program_patients', function($query) use($patient_id) {
            $query->where('patient_id', $patient_id);
        })->get();
        return response()->json(['sub_programs'=>$sub_programs],200);
    }
}

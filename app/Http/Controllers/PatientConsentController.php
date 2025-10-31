<?php

namespace App\Http\Controllers;

use App\Models\Consent;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientConsentController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('permission:'.PermissionEnum::MANAGE_PATIENTS);
    }
    public function index()
    {
        $patient = Patient::where('id',Auth::id())->first();
        $sub_program  = $patient->sub_program ?? null;
        $program = $sub_program ? $sub_program->program : null;
        $client = $program ? $program->client : null;
        $physician  = $patient->physician;

        return view('consent.index',[
            'patient'=>$patient,
            'sub_program'=>$sub_program,
            'program'=>$program,
            'client'=>$client,
            'physician'=>$physician
        ]);

    }

    public function store(Request $request)
    {
        $patient = Patient::where('id',$request->patient_id)->first();

        $consent_value = false;

        foreach ($patient->consents as $consent) {
            if(
                $consent->client_id  == $request->client_id
                and $consent->program_id  == $request->program_id
                and $consent->physician_id  == $request->physician_id
                and $consent->patient_id  == $request->patient_id
                and $consent->is_consent  == 1
            ){
                $consent_value = true;
            }
        }

        if($consent_value){
            return redirect()->route('consent.index')->with('success','Consent already exists');
        } else {
            $consent = new Consent();
            $consent->fill($request->only([
                'client_id',
                'program_id',
                'physician_id',
                'patient_id',
                'first_name',
                'family_name',
            ]));
            $consent->is_consent = 1;
            $consent->save();

            return redirect()->route('consent.index')->with('success','Consent Successfully');
        }

    }

}

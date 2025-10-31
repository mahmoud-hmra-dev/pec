<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Enums\PregnantEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubProgramPatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'patient_no'     => 'required|string',
            'birth_of_date'  => 'required|date',
            'height'         => 'required|numeric',
            'weight'         => 'required|numeric',
            'BMI'            => 'required|numeric',
            'is_over_weight' => 'required|boolean',
            'comorbidities'  => 'nullable|string',
            'gender'         => 'required|'.Rule::in(GenderEnum::MALE,GenderEnum::FEMALE,GenderEnum::NOT_TO_SAY),
            'is_eligible'    => 'required|boolean',
            'pregnant'       => $this->input('gender') == GenderEnum::FEMALE ? 'required' : 'nullable'.'|'.Rule::in(PregnantEnum::YES,PregnantEnum::NO,PregnantEnum::POSSIBILITY),

            'hospital_id'    => [ 'nullable', 'exists:hospitals,id', 'numeric',],
            'discuss_by'     => [$this->input('is_eligible') == 'on' ? 'required' : 'nullable', 'string',],
            'reporter_name'  => [$this->input('is_eligible') == 'on' ? 'required' : 'nullable', 'string',],
            'city'           => 'nullable|string',
            'address'        => 'nullable|string',
            'street'         => 'nullable|string',

            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'phone'          => 'required|string',
            'email'          => 'nullable|email',
            'country_id'     => 'required|exists:countries,id',

            'coordinator'          => [($this->input('is_eligible') == 'on' && $this->input('has_calls') && $this->input('call_every_day') ) > 0 ? 'required' : 'nullable', 'exists:country_service_providers,id',],
            'nurse'          => ['nullable', 'exists:country_service_providers,id',],

            'doctor_id'      => ['nullable', 'exists:doctors,id',],
            'pharmacy_id'      => [ 'nullable', 'exists:pharmacies,id',],

            'is_eligible_document'       => ['nullable', 'file', 'max:4076', 'mimes:jpg,jpeg,png,doc,pdf,docx'],


            'mc_chronic_diseases'=>  ['nullable', 'string','max:256'],
            'mc_medications'=>  ['nullable', 'string','max:256'],
            'mc_surgeries'=>  ['nullable', 'string','max:256'],
            'fmc_chronic_diseases'=>  ['nullable', 'string','max:256'],

            'is_consents'    =>     'required|boolean',
            'consent_document'       => ['nullable', 'file', 'max:4076', 'mimes:jpg,jpeg,png,doc,pdf,docx'],

            'documents' => ['array', 'nullable'],
            'documents.*.name' =>  [$this->input('documents.*.id') ? 'nullable' : 'required', 'file', 'max:4076', 'mimes:jpg,jpeg,png,doc,pdf,docx',],
            'documents.*.type'  =>  ['required', 'string','max:64'],
            'documents.*.description' => ['nullable', 'string','max:64'],

            'is_not_eligible'         => [$this->input('is_eligible') != 'on' ? 'required' : 'nullable',],
            'is_their_safety_report'    =>     'nullable|boolean',

            'safety_reports' => ['array', 'nullable'],
            'safety_reports.*.name' =>  [$this->input('safety_reports.*.id') ? 'nullable' : 'required', 'file', 'max:4076', 'mimes:jpg,jpeg,png,doc,pdf,docx',],
            'safety_reports.*.title'  =>  ['required', 'string','max:64'],
            'safety_reports.*.description' => ['nullable', 'string','max:64'],

            //'physician'      => 'required|exists:country_service_providers,id',
        ];
    }



    protected function prepareForValidation()
    {
        $is_over_weight = false;
        $is_eligible = false;
        $is_consents = null;
        $is_their_safety_report = false;

        if(isset($this->is_over_weight))
            $is_over_weight = $this->is_over_weight == 'on';
        if(isset($this->is_eligible))
            $is_eligible = $this->is_eligible == 'on';
        if(isset($this->is_consents))
            $is_consents = $this->is_consents == 'on';
        if(isset($this->is_their_safety_report))
            $is_their_safety_report = $this->is_their_safety_report == 'on';

        $this->merge([
            'is_over_weight' =>  $is_over_weight,
            'is_eligible' =>  $is_eligible,
            'is_consents' =>  $is_consents,
            'is_their_safety_report' =>  $is_their_safety_report,
        ]);
    }
}

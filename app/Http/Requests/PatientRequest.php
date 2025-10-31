<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
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
            'patient_no'         => 'required|numeric',
            /*'sub_program_id' => 'required|exists:sub_programs,id',*/
            'birth_of_date'         => 'required|date',
			'height'         => 'required|numeric',
			'weight'         => 'required|numeric',
			'BMI'            => 'required|numeric',
			'is_over_weight' => 'required|boolean',
			'comorbidities'  => 'required|string',
			'gender'         => 'required|'.Rule::in(GenderEnum::MALE,GenderEnum::FEMALE,GenderEnum::NOT_TO_SAY),
			'is_eligible'    => 'required|boolean',
			'is_pregnant'    => 'nullable|boolean',
            'hospital_id'    => 'nullable|numeric|exists:hospitals,id',
            /*'service_provider_type_id'   => 'required|numeric|exists:service_provider_types,id',*/
            'discuss_by'  => 'required|string',
            'reporter_name'  => 'required|string',
            'city'           => 'required|string',
			'address'        => 'required|string',
			'street'          => 'required|string',

            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'phone'          => 'required|string',
            'email'          => 'required|email|unique:users,email,'. $this->id,
            'password'       => 'nullable|confirmed|string',
            'image'          => 'image|nullable',
            'personal_email' => 'required|email|unique:users,personal_email,'. $this->id,
            'country_id'     => 'nullable|exists:countries,id',
        ];
    }



    protected function prepareForValidation()
    {
        $is_over_weight = false;
        $is_eligible = false;
        $is_pregnant = false;
        if(isset($this->is_over_weight))
            $is_over_weight = $this->is_over_weight == 'on';
        if(isset($this->is_eligible))
            $is_eligible = $this->is_eligible == 'on';
        if(isset($this->is_pregnant))
            $is_pregnant = $this->is_pregnant == 'on';
        $this->merge([
            'is_over_weight' =>  $is_over_weight,
            'is_eligible' =>  $is_eligible,
            'is_pregnant' =>  $is_pregnant,
        ]);
    }
}

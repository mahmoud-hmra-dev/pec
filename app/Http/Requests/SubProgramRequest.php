<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubProgramRequest extends FormRequest
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
			'name'                                   => 'required|string',
			'country_id'                             => 'required|exists:countries,id',
			'drug_id'                                => 'nullable|exists:drugs,id',
			'type'                                   => 'required|string',
            'target_number_of_patients'              => 'required|numeric',
            'eligible'                               => 'nullable',
            'has_calls'                              => 'nullable',
            'has_visits'                             => 'nullable',


            'is_follow_program_date'                 => 'nullable',
            'start_date'                             => 'nullable',
            'finish_date'                               => 'nullable',
            'treatment_duration'                => 'nullable|numeric',
            'program_initial'                        => 'required',
            'visit_every_day'                        => 'nullable|numeric',
            'call_every_day'                         => 'nullable|numeric',
            'has_FOC'                             => 'nullable',
            'cycle_period'                         =>  [$this->input('has_FOC') == 'on' ? 'required' : 'nullable', 'numeric',],
            'cycle_number'                         =>  [$this->input('has_FOC') == 'on' ? 'required' : 'nullable', 'numeric',],
            'cycle_reminder_at'                    =>  [$this->input('has_FOC') == 'on' ? 'required' : 'nullable', 'numeric',],
        ];
    }

    protected function prepareForValidation()
    {

    }
}

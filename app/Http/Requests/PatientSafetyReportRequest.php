<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientSafetyReportRequest extends FormRequest
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
            'name' =>  [$this->input('id') ? 'nullable' : 'required', 'file', 'max:4076', 'mimes:jpg,jpeg,png,doc,pdf,docx',],
            'title'  =>  ['required', 'string','max:64'],
            'description' => ['nullable', 'string','max:3000'],
            'sub_program_patient_id'     => 'required|exists:sub_program_patients,id',
        ];
    }
}

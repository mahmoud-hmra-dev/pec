<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Enums\VisitTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitsRequest extends FormRequest
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

            'patient_id'               => 'nullable|numeric|exists:patients,id',
            'sub_program_id'           => 'nullable|numeric|exists:sub_programs,id',
            'service_provider_type_id' => 'nullable|numeric|exists:service_provider_types,id',
            'activity_type_id'         => 'nullable|numeric|exists:activity_types,id',
            'should_start_at'          => 'required|date',
            'start_at'                 => 'required|date',
            'type_visit'         => 'required|'.Rule::in(VisitTypeEnum::Physical,VisitTypeEnum::Online),
            'meeting'         => 'nullable',

            'questions' => ['array', 'nullable'],
            'questions.*.question_id' => 'required|exists:questions,id',
            'questions.*.content' => ['required', 'max:2500'],
            'questions.*.content.*' => ['required', 'max:2500'],

            'documents' => ['array', 'nullable'],
            'documents.*.name' =>  [$this->input('documents.*.id') ? 'nullable' : 'required', 'file', 'max:4076', 'mimes:jpg,jpeg,png,doc,pdf,docx',],
            'documents.*.type'  =>  ['required', 'string','max:64'],
            'documents.*.description' => ['nullable', 'string','max:64'],

            'safety_reports' => ['array', 'nullable'],
            'safety_reports.*.name' =>  [$this->input('safety_reports.*.id') ? 'nullable' : 'required', 'file', 'max:4076', 'mimes:jpg,jpeg,png,doc,pdf,docx',],
            'safety_reports.*.title'  =>  ['required', 'string','max:64'],
            'safety_reports.*.description' => ['nullable', 'string','max:64'],
        ];
    }
}

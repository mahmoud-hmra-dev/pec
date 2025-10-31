<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
            /*'first_name'     => 'required|string',
			'last_name'      => 'required|string',*/
            'phone'          => 'nullable|string',
            'email' => [
                'required',
                'string',
                'max:64',
                Rule::unique('users')->ignore($this->id),
            ],
            'password'       => 'nullable|string',

            //'personal_email' => 'nullable|email',
            'city'           => 'nullable|string',
            'country_id'     => 'nullable|exists:countries,id',
            //'address'        => 'nullable|string',
            'client_address'     => 'required|string',
			'client_name' => 'required|string',
            'documents' => ['array', 'nullable'],
            'documents.*.name' =>  [$this->input('documents.*.id') ? 'nullable' : 'required', 'file', 'max:4076', 'mimes:doc,pdf,docx',],
            'documents.*.document_type_id'  => 'required|exists:document_types,id',
            'documents.*.description' => ['nullable', 'string','max:64'],

            'safety_report_document'       => ['nullable', 'file', 'max:4076', 'mimes:jpg,jpeg,png,doc,pdf,docx'],
        ];
    }

}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceTypeRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:64',
                Rule::unique('service_types')->ignore($this->id),
            ],
            'code' => [
                'required',
                'string',
                'max:64',
                Rule::unique('service_types')->ignore($this->id),
            ],
            'is_one' => 'required',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalRequest extends FormRequest
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
            'name' => 'required',
			'contact_person' => 'required',
			'phone' => 'required',
            'address'=>  ['nullable', 'string','max:1500'],
            'country_id' => 'required|exists:countries,id',
        ];
    }
}

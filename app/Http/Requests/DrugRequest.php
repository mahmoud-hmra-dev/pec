<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DrugRequest extends FormRequest
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
            'name'         => 'required|string|unique:drugs,name,' . $this->id,
			'client_id'   => 'required|numeric|exists:clients,id',
			'api_name'     => 'required|string',
			'drug_initial' => 'required|string',
			'drug_id'      => 'nullable|string',

        ];
    }
}

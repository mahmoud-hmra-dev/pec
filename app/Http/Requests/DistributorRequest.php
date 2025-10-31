<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DistributorRequest extends FormRequest
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
            'contract_person'=> 'required|string|max:64',
            'country_id' => 'required|exists:countries,id',
            'name'=> 'required|string|max:64',
            'email'=> 'required|email|unique:distributors',
            'phone'=> 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {

    }
}

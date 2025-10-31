<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceProviderTypeRequest extends FormRequest
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
        $rules = [
            'service_type_id' => [
                'nullable',
                'integer',
                Rule::requiredIf(!$this->filled('service_type_name')),
                Rule::exists('service_types', 'id'),
            ],
            'service_type_name' => ['nullable', 'string', 'max:255', 'required_without:service_type_id'],
            'countryServicesProvider' => ['array', 'nullable'],
            'countryServicesProvider.*.country_id'  => 'required|exists:countries,id',
            'countryServicesProvider.*.sub_program_id' => 'required|exists:sub_programs,id',
        ];

        if ($this->filled('service_type_id')) {
            $rules['service_type_id'][] = Rule::unique('service_provider_types')->where(function ($query) {
                return $query->where('service_provider_id', $this->service_provider_id)
                    ->where('service_type_id', $this->service_type_id);
            })->ignore($this->id);
        }

        return $rules;
    }
}

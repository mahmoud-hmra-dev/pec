<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramRequest extends FormRequest
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
			'name'               => 'required|string',
			'program_no'         => 'nullable|string',
			'map_id'             => 'nullable|string',
			'client_id'          => 'required|exists:clients,id',
			'service_provider_type_id' => 'nullable|exists:service_provider_types,id',
			'started_at'         => 'required|date',
			'ended_at'           => 'required|date',
            'drugs' => ['nullable', 'array'],
            'program_countries' => ['required', 'array', 'min:1'],
        ];
    }
}

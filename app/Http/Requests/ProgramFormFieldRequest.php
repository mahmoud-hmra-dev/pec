<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramFormFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string'],
            'field_key' => [
                'required',
                'alpha_dash',
                Rule::unique('program_form_fields', 'field_key')
                    ->ignore(optional($this->route('form_field'))->id)
                    ->where('program_id', optional($this->route('program'))->id),
            ],
            'field_type' => ['required', Rule::in(['text', 'textarea', 'number', 'date', 'select', 'checkbox'])],
            'is_required' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string'],
            'help_text' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_required' => $this->boolean('is_required'),
        ]);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_role' => ['required', 'string'],
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'title' => ['nullable', 'string'],
            'custom_title' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('title') === 'other' && ! $this->filled('custom_title')) {
                $validator->errors()->add('custom_title', __('Please provide a custom title when choosing Other.'));
            }

            if ($this->filled('custom_title') && strlen($this->input('custom_title')) > 191) {
                $validator->errors()->add('custom_title', __('Custom title may not be greater than 191 characters.'));
            }
        });
    }
}

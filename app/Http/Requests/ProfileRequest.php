<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'id'             => 'nullable|exists:users,id',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'phone'          => 'nullable|string',
            'email'          => 'required|email',
            'personal_email' => 'nullable|email',
            'city'           => 'nullable|string',
            'country_id'     => 'nullable|exists:countries,id',
            'address'        => 'nullable|string',
        ];
    }
}

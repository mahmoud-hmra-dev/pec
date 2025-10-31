<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'phone'          => 'nullable|string',
            'email'          => 'required|email|unique:users,email,'. $this->id,
            'password'       => 'nullable|confirmed|string',
            'personal_email' => 'nullable|email|unique:users,personal_email,'. $this->id,
            'city'           => 'nullable|string',
            'country_id'     => 'nullable|exists:countries,id',
            'address'        => 'nullable|string',
            'role'           => 'nullable|string',
        ];
    }
}

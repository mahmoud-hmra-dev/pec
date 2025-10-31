<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProviderRequest extends FormRequest
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
            'email'          => 'required|email',
            'password'       => 'nullable|confirmed|string',
            'image'          => 'nullable',
            'personal_email' => 'nullable|email',
            'address'        => 'nullable|string',
            'contract_type'         => ['nullable','string','max:64'],
            'contract_rate_price'   => ['nullable','numeric'],
            'contract_rate_price_per'   => ['nullable','string','max:64'],
            'attach_contract'       => ['nullable', 'file', 'max:4076', 'mimes:doc,pdf,docx'],
            'attach_cv'             => ['nullable', 'file', 'max:4076', 'mimes:doc,pdf,docx'],
            'city'                  => ['nullable','string','max:64'],
            'country_id'            => ['nullable','exists:countries,id'],
            'service_types'         => ['array', 'nullable'],
            'service_types.*'       => ['required','string','max:64'],
            'certificates_list'         => ['array', 'nullable'],
            'certificates_list.*.certificate'       => [$this->input('certificates_list.*.id') ? 'nullable' : 'required', 'file', 'max:4076', 'mimes:doc,pdf,docx',]

        ];
    }
}

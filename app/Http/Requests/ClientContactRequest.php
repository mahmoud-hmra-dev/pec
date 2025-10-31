<?php

namespace App\Http\Requests;

use App\Enums\ClientContactRoleEnum;
use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientContactRequest extends FormRequest
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
            'name'     => 'required|string|max:64',
			'email'         => 'required|email|max:64|unique:client_contacts,email,'. $this->id,
            'phone'          => 'nullable|string',
            'role'          => 'required|string|'.Rule::in(ClientContactRoleEnum::PSPManager,ClientContactRoleEnum::ProgramCoordinator,ClientContactRoleEnum::GeneralContact,ClientContactRoleEnum::SafetyCoordinator,ClientContactRoleEnum::FinanceDepartment),

        ];
    }

}

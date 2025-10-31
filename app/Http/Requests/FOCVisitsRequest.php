<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FOCVisitsRequest extends FormRequest
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

            'start_at'                 => 'required|date',
            'site_notified'           => 'required|'.Rule::in('Yes','No'),
            'notification_method'     => 'required|'.Rule::in('Email','Call'),
            'collected_from_pharmacy' => 'required|'.Rule::in('Yes','No'),
            'warehouse_call'          => 'required|'.Rule::in('Yes','No'),
            'attachment'              => 'nullable|file|max:4076|mimes:jpg,jpeg,png,doc,pdf,docx',
            'reminder_at'             => 'required|date',
        ];
    }
}

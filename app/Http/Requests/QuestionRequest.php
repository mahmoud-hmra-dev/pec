<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
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
            'question'=> 'required|max:1500',
            'question_type_id'=> 'required|exists:question_types,id',
            'category_id'=> 'required|exists:question_categories,id',
            'choices' => ['array', 'nullable'],
            'choices.*.choice' => ['nullable', 'string','max:64']

        ];
    }

    protected function prepareForValidation()
    {

    }
}

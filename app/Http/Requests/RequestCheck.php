<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestCheck extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'check' => 'require|boolean'
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'check.required' => 'поле check должно быть заполнено.',
            'check.boolean' => 'поле check является boolean.',
        ];
    }
}

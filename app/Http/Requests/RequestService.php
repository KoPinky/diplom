<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestService extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'serviceName' => ['required', 'string', 'max:255'],
            'price' => ['required', 'double'],
            'description' => ['string']
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'serviceName.required' => 'поле serviceName должно быть заполнено.',
            'serviceName.max' => 'поле serviceName должно содержать максимум 255 символов.',
            'serviceName.string' => 'поле serviceName является строкой.',

            'price.required' => 'поле price должно быть заполнено.',
            'price.double' => 'поле price является числом.',

            'description.string' => 'поле description является строкой.',
        ];
    }
}

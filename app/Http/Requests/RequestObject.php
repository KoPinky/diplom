<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestObject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'address' => 'require|string'
        ];
    }

    public function messages(): array
    {

        return [
            'address.require' => 'поле address должно быть заполнено',
            'address.string' => 'поле address должно быть строкой'
        ];
    }
}

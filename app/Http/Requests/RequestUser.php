<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestUser extends FormRequest
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
            'login' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'second_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'integer', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:8', 'confirmed'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'login.required' => 'поле login должно быть заполнено.',
            'login.max' => 'поле login должно содержать максимум 255 символов.',
            'login.string' => 'поле login является строкой.',

            'surname.required' => 'поле surname должно быть заполнено.',
            'surname.max' => 'поле surname должно содержать максимум 255 символов.',
            'surname.string' => 'поле surname является строкой.',

            'firstName.required' => 'поле firstName должно быть заполнено.',
            'firstName.max' => 'поле firstName должно содержать максимум 255 символов.',
            'firstName.string' => 'поле firstName является строкой.',

            'secondName.required' => 'поле secondName должно быть заполнено.',
            'secondName.max' => 'поле secondName должно содержать максимум 255 символов.',
            'secondName.string' => 'поле secondName является строкой.',

            'phone.required' => 'поле phone должно быть заполнено.',
            'phone.max' => 'поле phone должно содержать максимум 20 символов.',
            'phone.integer' => 'поле phone является целочисленным типом данных.',

            'password.required' => 'поле password должно быть заполнено.',
            'password.min' => 'поле password должно содержать минимум 8 символов.',
            'password.max' => 'поле password должно содержать максимум 50 символов.',
            'password.string' => 'поле password является строкой.',

            'email.required' => 'некорректный email.',
            'email.min' => 'поле email должно содержать максимум 255 символов.',
            'email.string' => 'поле email является строкой.'
        ];
    }
}

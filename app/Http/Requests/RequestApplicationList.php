<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestApplicationList extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'application_id' => 'required|integer',
            'material_id' => 'required|integer' ,
            'amount' => 'required|double'
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'application_id.required' => 'поле application_id должно быть заполнено.',
            'application_id.integer' => 'поле application_id является числом.',

            'material.required' => 'поле material должно быть заполнено.',
            'material.integer' => 'поле material является числом.',

            'amount.required' => 'поле amount должно быть заполнено.',
            'amount.integer' => 'поле amount является числом.'
        ];
    }
}

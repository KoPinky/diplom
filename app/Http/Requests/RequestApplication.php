<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestApplication extends FormRequest
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
            'role_id' => 'require|integer',
            'user_id' => 'require|integer',
            'object_id' => 'require|integer',
            'document_id' => 'require|integer',
            'status' => 'require|string',
        ];
    }


    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'role_id.required' => 'поле role_id должно быть заполнено.',
            'role_id.integer' => 'поле role_id является числом.',

            'user_id.required' => 'поле user_id должно быть заполнено.',
            'user_id.integer' => 'поле user_id является числом.',

            'object_id.required' => 'поле object_id должно быть заполнено.',
            'object_id.integer' => 'поле object_id является числом.',

            'document_id.required' => 'поле document_id должно быть заполнено.',
            'document_id.integer' => 'поле document_id является числом.',

            'status.required' => 'поле status должно быть заполнено.',
            'status.string' => 'поле status является строкой.',
        ];
    }
}

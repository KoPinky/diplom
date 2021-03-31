<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestWork extends FormRequest
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
            'order_id' => 'require|integer',
            'service_id' => 'require|integer',
            'status_id' => 'integer',
            'price' => 'require|double'
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'order_id.require' => 'поле name должно быть заполнено.',
            'order_id.integer' => 'поле order_id является целочисленным типом данных.',

            'service_id.required' => 'поле last_name должно быть заполнено.',
            'service_id.integer' => 'поле service_id является целочисленным типом данных.',

            'status_id.integer' => 'поле status_id является целочисленным типом данных.',

            'price.required' => 'поле password должно быть заполнено.',
            'price.double' => 'поле price является double.',
        ];
    }
}

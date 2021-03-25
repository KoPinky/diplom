<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestOrder extends FormRequest
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
            'dateContract' => 'required|date',
            'dateStart' => 'required|date',
            'dateEnd' => 'required|date',
            'status' => 'required|string',
            'object_id' => 'required|integer'
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'dateContract.required' => 'поле dateContract должно быть заполнено',
            'dateContract.date' => 'поле dateContract должно датой',
            'dateStart.required' => 'поле dateStart должно быть заполнено',
            'dateStart.date' => 'поле dateStart должно датой',
            'dateEnd.required' => 'поле dateEnd должно быть заполнено',
            'dateEnd.date' => 'поле dateEnd должно датой',
            'status.required' => 'поле status должно быть заполнено',
            'status.string' => 'поле status должно строкой',
            'object_id.required' => 'поле object_id должно быть заполнено',
            'object_id.integer' => 'поле object_id должно быть целочисленное неотрицательное число'
        ];
    }
}

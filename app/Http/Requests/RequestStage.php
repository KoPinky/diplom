<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestStage
 * @package App\Http\Requests
 */
class RequestStage extends FormRequest
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
            'stageName' => ['required', 'string'],
            'description' => ['string'],
            'service_id' => ['required', 'integer']
        ];
    }


    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'stageName.required' => 'поле stageName должно быть заполнено.',
            'stageName.string' => 'поле stageName является строкой.',

            'service_id.required' => 'поле service_id должно быть заполнено.',
            'service_id.integer' => 'поле service_id является числом.',

            'description.string' => 'поле description является строкой.',
        ];
    }
}

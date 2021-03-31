<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestReviews extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'reviews' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'reviews.required' => 'поле для отзыва должно быть заполнено',
            'reviews.string' => 'поле должно быть текстом',
        ];
    }
}

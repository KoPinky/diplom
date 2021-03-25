<?php

namespace App\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateReviews
{
    static public function validReview(Request $request) {
        $message = [
            'text.required' => 'Поле :attribute должно быть заполнено.',
            'text.string' => 'Поле :attribute должно быть строкой',
            'text.max' => 'Поле :attribute должно содержать максимум :max символов.',
            'text.min' => 'Поле :attribute должно содержать минимум :min символов.'
        ];

        Validator::validate($request->all(), [
            'text' => 'required|string|min:11|max:5000'
        ], $message);
    }
}

<?php

namespace App\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateLogin
{
    static public function validLog(Request $request)
    {
        $messages = [
            'required' => 'Поле :attribute должно быть заполнено.',
            'max' => 'Поле :attribute должно содержать максимум :max символов.',
            'min' => 'Поле :attribute должно содержать минимум :min символов.',
            'string' => 'Поле :attribute должно быть строкой.',
            'email.email' => 'Некорректный email.',
        ];

        Validator::validate($request->all(), [
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:6|max:8'
        ], $messages);
    }
}

<?php

namespace App\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateCall
{
    static public function validCall(Request $request) {
        $message = [

            'required' => 'Поле :attribute должно быть заполнено.',
            'string' => 'Поле :attribute должно быть строкой.',

            'name.regex' => 'Поле :attribute должно содержать только буквы.'
        ];

        Validator::validate($request->all(), [
            'name' => 'required|string|min:3|max:40|regex:/[A-Za-zА-Яа-я ]/',
            'phone' => ['required','string','regex:/^((\+7|7)+([0-9]){10})$/'],
        ], $message);
    }
}

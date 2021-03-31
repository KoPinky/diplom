<?php

namespace App\Exceptions\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ValidateRegistration
{
    static public function validReg(Request $request)
    {
        Validator::validate($request->all(), [
            'surname' => 'required|string|alpha|max:50',
            'first_name' => 'required|string|alpha|max:50',
            'second_name' => 'string|alpha|max:50',
            'phone' => ['required','string','regex:/^((\+7|7)+([0-9]){10})$/'],
            'role_id' => 'required|integer|min:2|max:4',
            'email' => 'required|string|email:rfc,dns|unique:users',
            'password' => [
                'string',
                'min:6',
                'max:8',
                Rule::requiredIf(function () use ($request) {
                    if (isset($request['password_auto']))
                        return !$request['password_auto'];
                    return true;
                })],
            'password_auto' => 'boolean',
            'experience' => 'string|max:2',
            'specialization' => 'string|alpha|max:40'
        ],
            [
                '*.required' => 'Поле должно быть заполнено'
            ]);
    }
}

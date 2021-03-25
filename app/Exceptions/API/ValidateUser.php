<?php

namespace App\Exceptions\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ValidateUser
{
    static public function validUser(Request $request)
    {
        Validator::validate($request->all(), [
            'surname' => 'string|alpha|max:50',
            'first_name' => 'string|alpha|max:50',
            'second_name' => 'string|alpha|max:50',
            'phone' => 'string|min:8|max:16|regex:/^[0-9\!#$%\\&\\[\\]@^~*()_+,.;:`]*$/',
            'role_id' => 'integer|min:2|max:4',
            'email' => 'string|email:rfc,dns|unique:users',
            'password' => 'string|min:6|max:8',
            'experience' => 'integer|max:2',
            'specialization' => 'string|alpha|max:40'
        ],
            [
                '*.required' => 'Поле должно быть заполнено'
            ]);
    }
}

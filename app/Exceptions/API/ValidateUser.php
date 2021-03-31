<?php

namespace App\Exceptions\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ValidateUser
{
    static public function validUser(Request $request)
    {
        $message = [
            'name.regex' => 'Имя должно состоять из букв',
        ];
        Validator::validate($request->all(), [
            'surname' => 'string|alpha|max:50',
            'first_name' => 'string|alpha|max:50',
            'second_name' => 'string|alpha|max:50',
            'phone' => ['string','regex:/^((\+7|7)+([0-9]){10})$/'],
            'role_id' => 'integer|min:2|max:4',
            'email' => 'string|email:rfc,dns|unique:users',
            'password' => 'string|min:6|max:8',
            'experience' => 'integer|max:2',
            'specialization' => 'string|alpha|max:40'
        ],$message);
    }
}

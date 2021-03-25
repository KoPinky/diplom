<?php

namespace App\Exceptions\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ValidateProfile
{
    static public function validUser(Request $request)
    {
        Validator::validate($request->all(), [
            'surname' => 'max:50|alpha',
            'first_name' => 'max:50|alpha',
            'second_name' => 'max:50|alpha',
            'phone' => 'min:8|max:16|regex:/([1-9])[0-9]/u',
            'email' => 'email|unique:users',
            'password' => 'min:6|max:8',
            'experience' => 'min:0|max:2|nullable',
            'specialization' => 'min:0|max:40|nullable'
        ]);
    }
}

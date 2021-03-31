<?php

namespace App\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateService
{
    static public function validService(Request $request)
    {
        $message = [
            'price.numeric' => 'поле price является числом.',
            'required' => 'Поле :attribute должно быть заполнено.',
            'string' => 'Поле :attribute должно быть строкой.',
        ];

        Validator::validate($request->all(), [
            'service_name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string'
        ], $message);
    }
}

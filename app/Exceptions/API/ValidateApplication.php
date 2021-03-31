<?php


namespace App\Exceptions\API;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateApplication
{
    static public function message()
    {
        return [
            'required' => 'Поле :attribute должно быть заполнено',
            'integer' => 'Поле :attribute должно быть целым число',
            'min' => 'Поле :attribute должно содержать минимум :min символов.',
            'max' => 'Поле :attribute должно содержать максимум :max символов.',
            'string' => 'Поле :attribute должно быть строкой',
            'alpha' => 'Поле :attribute должно содержать только буквы.',

            'amount.regex' => 'Поле amount должно содержать только цифры.',
        ];
    }

    static public function validateApp(Request $request)
    {
        Validator::validate($request->all(), [
            'object_id' => 'required|integer|min:1|max:100000000',
            'material' => 'required|string|max:200|alpha',
            'amount' => 'required|integer|min:1|max:10000|regex:/[0-9]/u'
        ], self::message());

    }

    static public function material(Request $request)
    {
        Validator::validate($request->all(), [
            'material' => 'required|string|alpha',
            'amount' => 'required|integer|min:0|regex:/[0-9]/u'
        ], self::message());
    }

    static public function status(Request $request)
    {
        Validator::validate($request->all(), [
            'status' => 'required|string|alpha',
        ], self::message());
    }
}

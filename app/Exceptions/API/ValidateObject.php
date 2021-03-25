<?php

namespace App\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/*
 * Для валидации необходимо добавть данный код в свой метод.
 *
     $valid = ValidateLogin::validLog($request);
        if ($valid['bool'] == true) {
            return $valid['errors'];
        }
 */

class ValidateObject
{
    static public function validObj(Request $request)
    {
        Validator::validate($request->all(), [
            'service_id' => 'required|integer|exists:services,id|min:0',
            'address' => 'required|string|regex:/[А-Яа-яЁё0-9]/u',
            'amountRoom' => 'required|integer|min:0',
            'area' => 'required|integer',
            'description' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'users' => ['required',
                'array',
                Rule::exists('App\Models\User', 'id')->where('role_id', [2, 3, 4])],

        ],
            [
                'address.required' => 'поле address должно быть заполнено.',
                'address.string' => 'поле address является строкой.',
                'address.regex' => 'поле address дрожно состоять из кирилици и чисел',

                'service_id.required' => 'поле service_id должно быть заполнено.',
                'service_id.integer' => 'Не соответствие типу данных! Поле service_id является числом.',
                'service_id.exists' => 'Такой услуги нет',
                'service_id.min' => 'service_id должно быть больше или равно 0',

                'amountRoom.required' => 'поле amountRoom должно быть заполнено.',
                'amountRoom.integer' => 'Не соответствие типу данных! Поле amountRoom является числом.',
                'amountRoom.min' => 'amountRoom должно быть больше или равно 0',

                'area.required' => 'поле area должно быть заполнено.',
                'area.string' => 'поле area является строкой.',

                'description.required' => 'поле description должно быть заполнено.',
                'description.string' => 'Не соответствие типу данных! Поле description является строкой.',

                'date_start.required' => 'поле date_start должно быть заполнено.',
                'date_start.date' => 'Не соответствие типу данных! Поле date_start является датой.',

                'date_end.required' => 'поле date_end должно быть заполнено.',
                'date_end.date' => 'Не соответствие типу данных! Поле date_end является датой.',

                'users.required' => 'поле users должно быть заполнено.',
                'users.*.integer' => 'Не соответствие типу данных! Поле id является числом.',
                'users.*.min' => 'Users должно быть больше или равно 0',
                'users.*.exists' => 'Данный пользователь не обладает нужными привелегиями или не зарегистрирован',
                'users.array' => 'Данное поле должно быть массивом',
            ]);

    }
}

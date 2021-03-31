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
            'area' => 'required|integer|regex:/^[1-9]\d*$/',
            'description' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'users' => ['required',
                'array',
                Rule::exists('App\Models\User', 'id')->where('role_id', [2, 3, 4])],

        ],
            [
                'address.required' => 'Введите адрес.',
                'address.string' => 'поле address является строкой.',
                'address.regex' => 'Адрес должен состоять из кирилицы и чисел',

                'service_id.required' => 'Выберите услугу.',
                'service_id.integer' => 'Не соответствие типу данных! Поле service_id является числом.',
                'service_id.exists' => 'Такой услуги нет',
                'service_id.min' => 'service_id должно быть больше или равно 0',

                'amountRoom.required' => 'Введите количество комнат',
                'amountRoom.integer' => 'Количество комнат должно быть числом.',
                'amountRoom.min' => 'amountRoom должно быть больше или равно 0',

                'area.required' => 'Введите площадь',
                'area.integer' => 'Площадь должна быть числом.',
                'area.regex' => 'Неверный формат ввода площади.',

                'description.required' => 'Введите описание.',
                'description.string' => 'Не соответствие типу данных! Поле description является строкой.',

                'date_start.required' => 'Введите дату начала.',
                'date_start.date' => 'Неверный формат даты.',

                'date_end.required' => 'Введите дату окончания.',
                'date_end.date' => 'Неверный формат даты.',

                'users.required' => 'Выберите пользователей.',
                'users.*.integer' => 'Не соответствие типу данных! Поле id является числом.',
                'users.*.min' => 'Users должно быть больше или равно 0',
                'users.*.exists' => 'Данный пользователь не обладает нужными привелегиями или не зарегистрирован',
                'users.array' => 'Данное поле должно быть массивом',
            ]);

    }
}

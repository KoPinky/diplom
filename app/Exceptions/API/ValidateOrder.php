<?php

namespace App\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ValidateOrder
{
    static private function setStartedAtAttribute($value)
    {
        return Carbon::createFromFormat('d/m/Y', $value);
    }

    static public function validOrder(Request $request) {
        $request->date_contract = self::setStartedAtAttribute($request['date_contract']);
        $request->date_start = self::setStartedAtAttribute($request['date_start']);
        $request->date_end = self::setStartedAtAttribute($request['date_end']);

        $message = [
            'date_contract.required' => 'Введите дату контракта.',
            'date_contract.date_format' => 'Формат даты должен быть d/m/Y',
            'date_contract.before' => 'дата должна быть сегодняшней',

            'date_start.required' => 'Введите дату начала.',
            'date_start.date_format' => 'Формат даты должен быть d/m/Y',
            'date_start.after' => 'дата начала должно быть позже даты начала контракта',

            'date_end.required' => 'Введите дату окончания.',
            'date_end.date_format' => 'Формат даты должен быть d/m/Y',
            'date_end.after' => 'дата начала должна быть позже даты начала старта',

            'status.required' => 'Введите статус',
            'status.string' => 'Статус должен быть строкой',
            'status.regex' => 'Статус должен содержать только буквы.',

            'object_id.required' => 'Выберите объект',
            'object_id.integer' => 'Id объекта должен быть целым число',
            'object_id.min' => 'Id объекта должен быть положительным числом',
        ];

        $result = Validator::make($request->all(), [
            'date_contract' => 'required|date_format:d/m/Y|before:today',
            'date_start' => 'required|date_format:d/m/Y|after:date_contract',
            'date_end' => 'required|date_format:d/m/Y|after:date_start',
            'status' => 'required|string|regex:/[А-Яа-яЁёA-Za-z]/u',
            'object_id' => 'required|integer|min:0',
        ], $message);

        if ($result->fails()) {
            return response()->json(['errors' => $result->getMessageBag()]);
        }
        return null;
    }
}

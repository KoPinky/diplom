<?php

namespace App\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
 * Для валидации необходимо добавть данный код в свой метод.
 *
     $valid = ValidateWork::validWork($request);
        if ($valid != true) {
            return $valid['errors'];
        }
 */

class ValidateWork
{
    static public function validWork(Request $request)
    {
        return
        Validator::validate($request->all(), [
            'works' => 'required|array',
            'works.*.stage_list_id' => 'required|integer|min:0|exists:stage_lists,id',
            'works.*.work_name' => 'required|string',
            'works.*.date_start' => 'required|date|after_or_equal:date_start_object|before_or_equal:works.*.date_end',
            'works.*.date_end' => 'required|date|before_or_equal:date_end_object',
        ],
            [
                'works.required' => 'поле works должен быть загружен.',
                'works.array' => 'поле works является массивом.',

                'works.*.stage_list_id.required' => 'Введите id этапа.',
                'works.*.stage_list_id.string' => 'Id этапа является числом.',
                'works.*.stage_list_id.min' => 'Id этапа должно быть больше 0.',
                'works.*.stage_list_id.exists' => 'Этап с таким id, не определен.',

                'works.*.work_name.required' => 'Введите название подэтапа.',
                'works.*.work_name.string' => 'Номер подэтапа является строкой.',

                'works.*.date_start.required' => 'Введите дату начала подэтапа.',
                'works.*.date_start.date' => 'Дата начала не соответствует формату.',

                'works.*.date_end.required' => 'Введите дату конца подэтапа.',
                'works.*.date_end.date' => 'Дата конца не соответствует формату.',
            ]);
    }

}

<?php

namespace App\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
 * Для валидации необходимо добавть данный код в свой метод.
 *
     $valid = ValidateLogin::validLog($request);
        if ($valid != true) {
            return $valid['errors'];
        }
 */

class ValidateStage
{
    static public function validStage(Request $request)
    {
        Validator::validate($request->all(), [
            'stages' => 'required|array',
            'stages.*.stage_name' => 'required|string',
        ],
            [
                'stages.required' => 'Заполните форму создания этапов',
                'stages.*.stage_name.required' => 'Введите название этапа',
                'stages.*.stage_name.string' => 'Название этапа является строкой.'
            ]);
    }

    static public function validComment(Request $request)
    {
        Validator::validate($request->all(),[
            'text' => 'required|string|regex:/[A-Za-zА-Яа-яЁё0-9 ]/u'
        ],[
            'text.required' => 'Поле text должно быть заполнено!',
            'text.string' => 'поле text должно быть строкой',
            'text.regex' => 'поле text должно состоять из букв и чисел'
        ]);
    }


}

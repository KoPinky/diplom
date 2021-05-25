<?php


namespace App\Exceptions\API;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ValidateDoc
{
    static public function validateDocument(array $input_data)
    {
        Validator::validate(
            $input_data, [
            'file.*' => 'required|mimetypes:image/png,image/jpeg,,image/jpg,image/x-ms-bmp,text/plain,application/msword,application/pdf,text/xml,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:20480'
        ], [
                'file.*.required' => 'Загрузите файл(ы)',
                'file.*.mimetypes' => 'Можно загружать файлы только в формате png, jpg, bmp, txt, doc, pdf, xml',
                'file.*.max' => 'Вы не можете загружать файлы размером более 20 МБ',
            ]
        );
    }

    static public function validateApplicationDocument(array $input_data)
    {
        Validator::validate(
            $input_data, [
            'file.*' => 'required|mimetypes:image/png,image/jpg,image/jpeg,image/x-ms-bmp|max:20480'
        ], [
                'file.*.mimetypes' => 'Можно загружать файлы только в формате png, jpg, bmp',
                'file.*.max' => 'Вы не можете загружать файлы размером более 20 МБ',
            ]
        );
    }

    static public function customCountValidation(int $count)
    {
        if ($count > 10) {
            return response()->json(['errors' => 'Вы не можете загрузить более 10 файлов.']);
        }
        return null;
    }

    static public function validateSearch(array $input_data)
    {
        Validator::validate(
            $input_data, [
            'searchString' => 'string',
            'startDate' => 'date',
            'endDate.*' => 'date',
            'documentType.*' => 'string|'
        ], [
                'searchString.string' => 'Поле searchString является строкой',
                'startDate.date' => 'Поле startDate является датой',
                'endDate.date' => 'Поле endDate является датой',
                'documentType.string' => 'Поле documentType является строкой'
            ]
        );
    }

}

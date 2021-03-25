<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class RequestDocument extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:doc,docm,docx,pdf,xls,xlm,xla,xlc,xlt,xlw|max:40960',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Документ должен быть загружен.',
            'file.max' => 'Документ должен быть максимум 40 Мб.',
            'file.mimes' => 'Формат Документа должен быть одним из следующих: doc,docm,docx,pdf,xls,xlm,xla,xlc,xlt,xlw.
             Удостоверьтись, что документ имеет один из этих форматов'
        ];
    }
}

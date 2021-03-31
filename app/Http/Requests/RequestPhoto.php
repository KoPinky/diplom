<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class RequestPhoto extends FormRequest
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
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:20480',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Изображение должно быть загружено.',
            'file.max' => 'Изображение должно быть максимум 20 Мб.',
            'file.mimes' => 'Формат изображение должен быть одним из следующих: jpeg, jpg, png.
             Удостоверьтись, что изображение имеет один из этих форматов'
        ];
    }
}

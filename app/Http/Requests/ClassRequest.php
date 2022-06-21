<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'class_code'    =>  'unique:clazzs'
        ];
    }

    public function messages()
    {
        return [
          'unique'  =>  ':attribute đã tồn tại!'
        ];
    }

    public function attributes()
    {
        return [
            'class_code'    =>  'mã lớp'
        ];
    }
}

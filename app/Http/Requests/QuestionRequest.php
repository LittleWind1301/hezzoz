<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuestionRequest extends FormRequest
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
            'question_content'  =>  'required',
            'level'             =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'required'  =>  'Vui lòng nhập :attribute!',
            'level.required'    =>  'Vui lòng chọn :attribute',
        ];
    }

    public function attributes()
    {
        return [
            'question_content'  =>  'nội dung câu hỏi',
            'level'             =>  'mức độ câu hỏi',
        ];
    }

    protected function failedAuthorization()
    {
        //throw new AuthorizationException('Bạn không có quyền sử dụng chức năng này');
        throw new HttpResponseException(redirect('/admin/'));
    }
}

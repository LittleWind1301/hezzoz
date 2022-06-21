<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CourseRequest extends FormRequest
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
            'course_code'=>  'required|unique:courses',
            'course_name'=>  'required',
        ];
    }

    public function messages()
    {
        return [
            'required'  =>  ':attribute chưa nhập thông tin',
            'unique'    =>  ':attribute đã tồn tại'
        ];
    }

    public function attributes()
    {
        return [
            'course_code'=>'Mã bộ môn',
            'course_name'=>'Tên bộ môn'
        ];
    }

    public function withValidator($validator){
        $validator->after(function ($validator){
            if($validator->errors()->count()>0){
                $validator->errors()->add('msg', 'Đã có lỗi xảy ra, vui lòng kiểm tra lại!');
            }
        });
    }
}

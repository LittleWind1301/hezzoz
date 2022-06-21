<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FacultyRequest extends FormRequest
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
            'faculty_code'=> 'required|unique:faculties',
            'faculty_name'=> 'required'
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
            'faculty_code'=>'Mã khoa',
            'faculty_name'=>'Tên khoa'
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

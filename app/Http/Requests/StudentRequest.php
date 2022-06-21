<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App;
class StudentRequest extends FormRequest
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
            'email'         =>  'required|unique:users',
            'password'      =>  'required|min:3|max:10',
            'student_id'    =>  'required|unique:profile_students',
            'student_name'  =>  'required',
            'cmnd'          =>  'required|',
            'dateOfBirth'   =>  'required',
            'gender'        =>  'required',
            'phone_number'   =>  'required|numeric',
            'province'      =>  'required',
            'address'       =>  'required',
            'yearOfAdmission'=> 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'required'  =>  'Chưa nhập :attribute',
            'unique'    =>  ':attribute đã tồn tại',
            'min'       =>  'độ dài :attribute từ 3 đến 10 kí tự',
            'max'       =>  'độ dài :attribute từ 3 đến 10 kí tự',
            'numeric'   =>  ':attribute không hợp lệ'
        ];
    }

    public function attributes()
    {
        return [
            'password'      =>  'Mật khẩu',
            'student_id'    =>  'Mã sinh viên',
            'student_name'  =>  'Họ tên',
            'cmnd'          =>  'Số SMND',
            'dateOfBirth'   =>  'Ngày sinh',
            'gender'        =>  'Giới tính',
            'phone_number'   =>  'Số điện thoại',
            'province'      =>  'Quê quán',
            'address'       =>  'Địa chỉ',
            'yearOfAdmission'=> 'Khoá học'
        ];
    }

}

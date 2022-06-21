<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{


    public function loginStudent(){
        if(auth()->check()){
            return redirect()->route('home');
        }
        return view('student.login');
    }

    public function postLoginStudent(Request $request){

        $remember = $request->has('remember-me')?true:false;

        $this->validate($request, [
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(auth()->attempt([
            'email'=>$request->email,
            'password'=>$request->password
        ], $remember))
        {

        }

        //Session::flash('error', 'Email hoặc Password không chính xác');

        return redirect()->back();
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect()->to('dang-nhap');
    }
}

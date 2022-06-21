<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('isAdmin');
//    }

    public function loginAdmin(){
        if(auth()->check()){
            if(Auth::user()->utype == 'ADM'
                || Auth::user()->utype == 'MASTER'
                || Auth::user()->utype == 'LECTURERS'
                || Auth::user()->utype == 'FACULTY'
                || Auth::user()->utype == 'COURSE'
                || Auth::user()->utype == 'EDU')
            {
                return redirect()->route('admin');
            }

            elseif(Auth::user()->utype == 'STU')
            {
                return redirect()->route('home');
            }
        }
        return view('admin.login');
    }

    public function postLoginAdmin(Request $request){

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
            if(Auth::user()->utype == 'ADM'
                || Auth::user()->utype == 'MASTER'
                || Auth::user()->utype == 'LECTURERS'
                || Auth::user()->utype == 'FACULTY'
                || Auth::user()->utype == 'COURSE'){
                return redirect()->route('admin');
            }
            elseif(auth()->user()->utype == 'STU'){
                return redirect()->route('home');
            }
        }

        Session::flash('error', 'Email hoặc Password không chính xác');

        return redirect()->back();
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect()->to('login');
    }

    public function changePassword(Request $request){
        return view('admin.changePwd');
    }

    public function postNewPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_pwd'   =>  'required|min:3|max:256',
            'new_pwd'   =>  'required|min:6|max:256|same:confirm_pwd',
            'confirm_pwd'=> 'required|min:6|max:256'
        ],[
            'required'  =>  'Chưa nhập thông tin',
            'min'       =>  'Độ dài mật khẩu từ 6-256 kí tự',
            'max'       =>  'Độ dài mật khẩu từ 6-256 kí tự',
            'same'      =>  'Mật khẩu mới không trùng khớp'
        ]);
        $validator->validate();

        try {
            DB::beginTransaction();
            $userById = User::find(Auth::user()->id);

            if(auth()->attempt([
                'email'=>$userById->email,
                'password'=>$request->old_pwd
            ])){
                $userById->update([
                    'password'  =>  bcrypt($request->new_pwd)
                ]);
                DB::commit();
                return response()->json([
                    'status'    =>  200,
                    'messages'  =>  'Thay đổi mật khẩu thành công'
                ]);
            }else{
                return response()->json([
                    'status'    =>  500,
                    'messages'  =>  'Mật khẩu cũ không chính xác'
                ]);
            }
        }catch (\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'    =>  500,
                'messages'  =>  $ex->getMessage()
            ]);
        }
    }
}

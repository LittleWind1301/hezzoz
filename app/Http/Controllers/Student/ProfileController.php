<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\ProfileStudent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(){

        $user = User::find(Auth::user()->id);
        $userProfile = $user->profileStudent;
        return view('student.profile.index', [
            'user'         =>  $user,
            'userProfile'  =>  $userProfile
        ]);
    }

    public function changePassword(Request $request){
        return view('student.profile.changePwd');
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

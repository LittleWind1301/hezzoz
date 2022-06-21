<?php

namespace App\Http\Controllers\Admin;

use App\course;
use App\faculty;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\User_course;
use App\User_faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    private $user;
    private $user_faculty;
    private $user_course;
    public function __construct(User $user, User_faculty $user_faculty, User_course $user_course)
    {
        $this->user = $user;
        $this->user_faculty = $user_faculty;
        $this->user_course = $user_course;
    }

    public function listFaculty(){
        $listFaculty = faculty::all();
        return view('admin.account.listFaculty', [
            'listFaculty'=>$listFaculty]);
    }

    public function courseOfFaculty($faculty_id){
        $listCourse = faculty::find($faculty_id)->courses;
        return view('admin.account.listCourse', [
            'listCourse'=>$listCourse]);
    }

    public function accountOfFaculty($faculty_id){

        $facultyById = faculty::find($faculty_id);

        return view('admin.account.listAccountOfFaculty', [
            'facultyById'=>$facultyById]);
    }

    public function storeAccountFaculty(Request $request){

        try{
            DB::beginTransaction();
            $data = [
                'email'         =>$request->user_email,
                'password'      =>bcrypt($request->user_pwd),
                'remember_token'=>' ',
                'status'        =>true,
                'utype'         =>'FACULTY'
            ];

            $createAccount = $this->user->create($data);


            $this->user_faculty->create([
                'user_id'=>$createAccount->id,
                'faculty_id'=>$request->faculty_id
            ]);

            DB::commit();
            return response()->json([
                'status'=>200
            ]);

        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>0
            ]);
        }
    }

    public function fetchAccountFaculty(Request $request){

        $user_Faculty = $this->user_faculty::all()->where('faculty_id', $request->faculty_id);

        $output = '';
        if($user_Faculty->count()>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($user_Faculty as $item){
                $userById = $this->user::find($item->user_id);
                $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$userById->email.'</td>
                                    <td>
                                        <a href="javascript:;" id="" onclick="deleteItem('.$item->id.')" class="text-danger mx-1 deleteIcon">
                                            <i class="bi-trash h4"></i>
                                        </a>
                                    </td>

                                </tr>';
            }
            $output .='</tbody></table>';
            echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function deleteAccountFaculty(Request $request){

        $user_faculty = $this->user_faculty::find($request->id);

        $this->user::destroy($user_faculty->user_id);
        $this->user_faculty::destroy($request->id);

        return response()->json([
            'status'=>200
        ]);
    }

    public function accountOfCourse($course_id){
        $courseById = course::find($course_id);

        return view('admin.account.listAccountOfCourse', [
            'courseById'=>$courseById]);
    }

    public function storeAccountCourse(Request $request){
        try{
            DB::beginTransaction();
            $data = [
                'email'         =>$request->user_email,
                'password'      =>bcrypt($request->user_pwd),
                'remember_token'=>' ',
                'status'        =>true,
                'utype'         =>'COURSE'
            ];

            $createAccount = $this->user->create($data);


            $this->user_course->create([
                'user_id'=>$createAccount->id,
                'course_id'=>$request->course_id
            ]);

            DB::commit();
            return response()->json([
                'status'=>200
            ]);

        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>0
            ]);
        }
    }

    public function fetchAccountCourse(Request $request){

        $user_course = $this->user_course::all()->where('course_id', $request->course_id);

        $output = '';
        if(count($user_course)>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($user_course as $item){
                $userById = $this->user::find($item->user_id);
                $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$userById->email.'</td>
                                    <td>
                                        <a href="javascript:;" id="" onclick="deleteItem('.$item->id.')" class="text-danger mx-1 deleteIcon">
                                            <i class="bi-trash h4"></i>
                                        </a>
                                    </td>

                                </tr>';
            }
            $output .='</tbody></table>';
            echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function deleteAccountCourse(Request $request){

        $user_faculty = $this->user_course::find($request->id);

        $this->user::destroy($user_faculty->user_id);
        $this->user_course::destroy($request->id);

        return response()->json([
            'status'=>200
        ]);
    }
}

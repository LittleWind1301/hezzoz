<?php

namespace App\Http\Controllers\Admin;

use App\course;
use App\faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\ProfileStudent;
use App\User;
use App\User_course;
use App\User_faculty;
use Illuminate\Http\Request;
use App\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    private $student;
    private $profileStudent;

    public function __construct(User $student, ProfileStudent $profileStudent)
    {
        $this->student = $student;
        $this->profileStudent = $profileStudent;
    }
    public function listFaculty(){

        if(Auth::user()->utype == 'MASTER')
            $listFaculty = faculty::all();
        elseif(Auth::user()->utype == 'FACULTY'){
            $facultyId = User_faculty::all()->where('user_id', Auth::user()->id)->first()->faculty_id;
            $listFaculty = faculty::all()->where('id', $facultyId);
        }elseif(Auth::user()->utype == 'COURSE'){
            $courseId = User_course::all()->where('user_id', Auth::user()->id)->first()->course_id;
            $courseById = course::find($courseId);
            $facultyId  = faculty::find($courseById->faculty_id);
            $listFaculty = faculty::all()->where('id', $facultyId->id);
        }

        return view('admin.student.listFaculty', [
            'listFaculty'=>$listFaculty]);
    }

    public function studentOfFaculty($faculty_id){
        $facultyById = faculty::find($faculty_id);
        return view('admin.student.listStudent', [
            'facultyById'=>$facultyById
        ]);
    }

    public function fetchAll(Request $request){

        $listStudent = $this->profileStudent->where('faculty_id', $request->faculty_id)->get();
        $data = [];
        foreach ($listStudent as $item){
            $item->user;
            $data[] = $item;
        }


        $output = '';

        if(count($data)>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Họ Tên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Địa Chỉ</th>
                                    <th>Địa Chỉ Email</th>
                                    <th>Giới tính</th>
                                    <th>Ngày Sinh</th>
                                    <th>Khoá</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($data as $item){
                $output .=
                            '<tr>
                                <td>'.$item->student_id.'</td>
                                <td>'.$item->student_name.'</td>
                                <td>'.$item->phoneNumber.'</td>
                                <td>'.$item->address.'</td>
                                <td>'.$item->user->email.'</td>
                                <td>'.$item->gender.'</td>
                                <td>'.$item->dateOfBirth.'</td>
                                <td>K'.$item->yearOfAdmission.'</td>
                                <td>
                                    <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editStudentModal">
                                            <i class="bi-pencil-square h4"></i>
                                    </a>

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

    //handle insert student ajax request
    public function store(StudentRequest $request){
        try{
            DB::beginTransaction();
            $userData = [
                'email'             =>  $request->email,
                'password'          =>  bcrypt($request->password),
                'status'            =>  true,
                'remember_token'    =>  '',
                'utype'             =>  'STU'
            ];
            $createUser = $this->student->create($userData);

            $this->profileStudent->create([
                'user_id'       =>  $createUser->id,
                'student_id'    =>  $request->student_id,
                'cmnd'          =>  $request->cmnd,
                'dateOfBirth'   =>  $request->dateOfBirth,
                'gender'        =>  $request->gender,
                'phoneNumber'   =>  $request->phone_number,
                'province'      =>  $request->province,
                'address'       =>  $request->address,
                'yearOfAdmission'=> $request->yearOfAdmission,
                'faculty_id'    =>  $request->faculty_id,
                'student_name'  =>  $request->student_name
            ]);
            DB::commit();
            return response()->json([
                'status'=>200
            ]);

        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>500,
                'msg'=>$ex->getMessage()
            ]);
        }

    }

    public function edit(Request $request){

        $listStudent = $this->profileStudent::find($request->id);
        $listStudent->user;

        return response()->json($listStudent);
    }

    public function update(Request $request){

        $profileStudentById = $this->profileStudent::Find($request->id);

        $studentData = [
            'student_id'    =>  $request->student_id,
            'cmnd'          =>  $request->cmnd,
            'dateOfBirth'   =>  $request->dateOfBirth,
            'gender'        =>  $request->gender,
            'phoneNumber'   =>  $request->phoneNumber,
            'province'      =>  $request->province,
            'address'       =>  $request->address,
            'yearOfAdmission'=> $request->yearOfAdmission,
            'student_name'  =>  $request->name,
        ];

        //return response()->json($studentData);
        $profileStudentById->update($studentData);

        $userOfProfileStudent = $profileStudentById->user;

        $userOfProfileStudent->update([
            'email'=>$request->email
        ]);

        return response()->json([
            'status'=>200
        ]);

    }

    public function delete(Request $request){
        try{
            $profileStudentById = $this->profileStudent::find($request->id);
            $profileStudentById->user->delete();
            $profileStudentById->delete();

            return response()->json([
                'code'=>200,
                'message'=>'success'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'code'=>500,
                'message'=>'fail'
            ], 500);
        }
    }
    public function storeImport(Request $request){
        $rules = [
            'file'=>'required|max:5000|mimes:xlsx, xls, csv'
        ];
        $messages = [
            'file.required'=>'Chưa thêm file',
            'file.mimes'=>'file không hợp lệ'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->validate();

        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray([], $path);


        foreach ($data as $key => $value){
            unset($value[0]);
            if(count($value)>0) {

                foreach ($value as $row) {
                    if (!empty($row[0])) {
                        try {
                            DB::beginTransaction();
                            $createUser = $this->student->create([
                                'email' => $row[0],
                                'password' => bcrypt($row[1]),
                                'status' => true,
                                'remember_token' => '',
                                'utype' => 'STU'
                            ]);

                            $this->profileStudent->create([
                                'user_id' => $createUser->id,
                                'student_id' => $row[2],
                                'student_name' => $row[3],
                                'cmnd' => $row[4],
                                'dateOfBirth' => $row[5],
                                'gender' => $row[6],
                                'phoneNumber' => $row[7],
                                'province' => $row[8],
                                'address' => $row[9],
                                'yearOfAdmission' => $row[10],
                                'faculty_id' => $request->faculty_id,
                            ]);
                            DB::commit();
                        } catch (\Exception $ex) {
                            DB::rollBack();
                            return response()->json([
                                'status' => 0,
                                'messages' => $ex->getMessage()
                            ]);
                        }
                    }
                }
            }else{
                return response()->json([
                    'status'=>500,
                    'messages'=>'Dữ liệu trống!'
                ]);
            }
        }
        return response()->json([
            'status'=>200,
            'messages'  =>  'Thêm dữ liệu thành công'
        ]);


    }
}














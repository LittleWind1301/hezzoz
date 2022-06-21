<?php

namespace App\Http\Controllers\Admin;

use App\course;
use App\faculty;
use App\Http\Controllers\Controller;
use App\ProfileLecturers;
use App\ProfileStudent;
use App\User;
use App\User_course;
use App\User_faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LecturersController extends Controller
{
    private $lecturers;
    private $profileLecturers;

    public function __construct(User $lecturers, ProfileLecturers $profileLecturers)
    {
        $this->lecturers = $lecturers;
        $this->profileLecturers = $profileLecturers;
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

        return view('admin.lecturers.listFaculty', [
            'listFaculty'=>$listFaculty]);
    }

    public function lecturersOfCourse($course_id){
        $courseById = course::find($course_id);
        return view('admin.lecturers.listLecturers', [
            'courseById'=>$courseById
        ]);
    }

    public function fetchAll(Request $request){


        $listLecturers = $this->profileLecturers->where('course_id', $request->course_id)->get();
        $data = [];
        foreach ($listLecturers as $item){
            $item->user;
            $data[] = $item;
        }


        $output = '';

        if(count($data)>0){
            $output .= '<table class="table table-striped table-hover table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã GV</th>
                                    <th>Họ Tên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Địa Chỉ</th>
                                    <th>Địa Chỉ Email</th>
                                    <th>Giới tính</th>
                                    <th>Ngày Sinh</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($data as $item){
                $output .=
                    '<tr>
                                <td>'.$item->id.'</td>
                                <td>'.$item->lecturers_id.'</td>
                                <td>'.$item->lecturers_name.'</td>
                                <td>'.$item->phoneNumber.'</td>
                                <td>'.$item->address.'</td>
                                <td>'.$item->user->email.'</td>
                                <td>'.$item->gender.'</td>
                                <td>'.$item->dateOfBirth.'</td>
                                <td>
                                    <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editModal">
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

    public function store(Request $request){
//        $file = $request->file('student_image');
//        $fileName = time().'.'.$file->getClientOriginalExtension();
//        $file->storeAs('public/images', $fileName);

        try{
            DB::beginTransaction();
            $createUser = $this->lecturers->create([
                'email'             =>  $request->email,
                'password'          =>  bcrypt($request->password),
                'status'            =>  true,
                'remember_token'    =>  '',
                'utype'             =>  'LECTURERS'
            ]);

            $this->profileLecturers->create([
                'user_id'       =>  $createUser->id,
                'lecturers_id'  =>  $request->lecturers_id,
                'cmnd'          =>  $request->cmnd,
                'dateOfBirth'   =>  $request->dateOfBirth,
                'gender'        =>  $request->gender,
                'phoneNumber'   =>  $request->phoneNumber,
                'province'      =>  $request->province,
                'address'       =>  $request->address,
                'course_id'    =>  $request->course_id,
                'lecturers_name'  =>  $request->name
            ]);
            DB::commit();
            return response()->json([
                'status'=>200,
                'messages'  =>  'Thêm giảng viên thành công!'
            ]);

        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>0,
                'messages'  =>  'Có lỗi xảy ra!'
            ]);
        }

    }

    public function edit(Request $request){

        $listLecturers = $this->profileLecturers::find($request->id);
        $listLecturers->user;
        return response()->json($listLecturers);
    }

    public function update(Request $request){

        try{
            DB::beginTransaction();
            $profileLecturersById = $this->profileLecturers::find($request->id);
            $lecturersData = [
                'lecturers_id'    =>  $request->lecturers_id,
                'cmnd'          =>  $request->cmnd,
                'dateOfBirth'   =>  $request->dateOfBirth,
                'gender'        =>  $request->gender,
                'phoneNumber'   =>  $request->phoneNumber,
                'province'      =>  $request->province,
                'address'       =>  $request->address,
                'lecturers_name'  =>  $request->name,
            ];
            $profileLecturersById->update($lecturersData);

            $userOfProfileLecturers = $profileLecturersById->user;

            $userOfProfileLecturers->update([
                'email'=>$request->email
            ]);

            DB::commit();
            return response()->json([
                'status'=>200,
                'messages'  =>  'Cập nhật thông tin thành công'
            ]);

        }catch (\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>500,
                'messages'  =>  $ex->getMessage()
            ]);
        }
    }

    public function delete(Request $request){
        try{
            $profileLecturersById = $this->profileLecturers::find($request->id);
            $profileLecturersById->user->delete();
            $profileLecturersById->delete();

            return response()->json([
                'status'=>200,
                'message'=>'Xoá dữ liệu thành công'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'status'=>500,
                'message'=>$ex->getMessage()
            ]);
        }
    }

    public function profile(){

        $user = User::find(Auth::user()->id);
        $userProfile = $user->profileLecturers;

        $course = course::find($userProfile->course_id);
        return view('admin.lecturers.profile', [
            'user'         =>  $user,
            'userProfile'  =>  $userProfile,
            'course'   =>  $course
        ]);
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

                            $createUser = $this->lecturers->create([
                                'email' => $row[0],
                                'password' => bcrypt($row[1]),
                                'status' => true,
                                'remember_token' => '',
                                'utype' => 'LECTURERS'
                            ]);

                            $this->profileLecturers->create([
                                'user_id' => $createUser->id,
                                'lecturers_id' => $row[2],
                                'lecturers_name' => $row[3],
                                'cmnd' => $row[4],
                                'dateOfBirth' => $row[5],
                                'gender' => $row[6],
                                'phoneNumber' => $row[7],
                                'province' => $row[8],
                                'address' => $row[9],
                                'course_id' => $request->course_id,
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

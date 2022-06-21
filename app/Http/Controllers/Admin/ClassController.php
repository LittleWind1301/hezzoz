<?php

namespace App\Http\Controllers\Admin;

use App\Clazz;
use App\course;
use App\Exam;
use App\Exam_class;
use App\faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassRequest;
use App\StudentAnswer;
use App\Subject;
use App\SubjectV2;
use App\User_course;
use App\User_faculty;
use Dotenv\Validator;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\RequestInterface;

class ClassController extends Controller
{
    private $clazz;
    private $exam_class;
    private $studentAnswer;

    public function __construct(Clazz $clazz, Exam_class $exam_class, StudentAnswer $studentAnswer)
    {
        $this->clazz = $clazz;
        $this->exam_class = $exam_class;
        $this->studentAnswer = $studentAnswer;
    }

    public function listFaculty(){

        $courseId = '';

        if(Auth::user()->utype == 'MASTER' || Auth::user()->utype == 'EDU')
        {
            $listFaculty = faculty::all();
        }
        elseif(Auth::user()->utype == 'FACULTY')
        {
            $facultyId = User_faculty::all()->where('user_id', Auth::user()->id)->first()->faculty_id;
            $listFaculty = faculty::all()->where('id', $facultyId);
        }
        elseif(Auth::user()->utype == 'COURSE')
        {
            $courseId = User_course::all()->where('user_id', Auth::user()->id)->first()->course_id;
            $courseById = course::find($courseId);
            $facultyId  = faculty::find($courseById->faculty_id);
            $listFaculty = faculty::all()->where('id', $facultyId->id);
        }

        return view('admin.class.listFaculty', [
            'listFaculty'=>$listFaculty,
            'courseId' => $courseId]);
    }

    public function classesOfSubject($subject_id)
    {
        $subject = SubjectV2::find($subject_id);

        return view('admin.class.listClass', [
            'subject' => $subject
        ]);
    }



    public function fetchAll(Request $request){

        $subjectById = SubjectV2::find($request->subject_id);

        $classOfSubject = $subjectById->classes;

        $output = '';
        if(count($classOfSubject)>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã lớp học phần</th>
                                    <th>Tên lớp học phần</th>
                                    <th>Khoá</th>
                                    <th>Tổng số sinh viên</th>
                                    <th>Học kì</th>
                                    <th>Quản lý sinh viên</th>
                                    <th>Quản lý lịch thi</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';

                $i = 0;
                foreach ($classOfSubject as $item){
                    $i ++;
                    $output .='<tr>
                                    <td>'.$i.'</td>
                                    <td>'.$item->class_code.'</td>
                                    <td>'.$item->class_name.'</td>
                                    <td>'.$item->courseNumber.'</td>
                                    <td>'.$item->total_student.'</td>
                                    <td>'.$item->semester.'</td>
                                    <td>
                                        <a href="'.route('studentOfClasses.studentOfClass', ['class_id'=>$item->id]).'" class="btn btn-success btn-sm">
                                            <i class="fa fa-folder"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="'.route('schedules.schedulesOfClass', ['class_id'=>$item->id]).'" class="btn btn-success btn-sm">
                                            <i class="fa fa-folder"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editClassModal">
                                            <i class="bi-pencil-square h4"></i>
                                        </a>

                                        <a href="javascript:;" id="'.$item->id.'" onclick="deleteItem('.$item->id.')" class="text-danger mx-1 deleteIcon">
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

    public function store(ClassRequest $request){
        try {
            DB::beginTransaction();
            $this->clazz->create([
                'class_name'   =>   $request->class_name,
                'class_code'   =>   $request->class_code,
                'courseNumber' =>   $request->courseNumber,
                'subject_id'   =>   $request->subject_id,
                'semester'     =>   $request->semester,
                'total_student'=>0
            ]);
            DB::commit();
            return response()->json([
                'status'    =>  200,
                'messages'  =>  'Tạo lớp học phần mới thành công'
            ]);
        }catch (\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>500,
                'messages'=>$ex->getMessage()
            ]);
        }
    }

    public function edit(Request $request){
        $id = $request->id;
        $class = $this->clazz::find($id);
        return response()->json($class);
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
            $class = $this->clazz::find($request->class_id);

            $data = [
                'class_name'    =>  $request->class_name,
                'class_code'    =>  $request->class_code,
                'courseNumber'  =>  $request->courseNumber,
                'semester'      =>  $request->semester,
            ];

            $class->update($data);
            DB::commit();
            return \response()->json([
                'status'    =>  '200',
                'messages'  =>  'Cập nhật thành công'
            ]);
        }catch (\Exception $ex) {
            DB::rollBack();
            return \response()->json([
                'status' => '500',
                'messages' => $ex->getMessage()
            ]);
        }
    }

    //delete class ajax request
    public function delete(Request $request){
        try{
            DB::beginTransaction();
            $this->clazz::find($request->id)->delete();
            DB::commit();
            return response()->json([
                'status'=>200,
                'message'=>'Xoá dữ liệu thành công'
            ]);
        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>500,
                'message'=>'Thất bại'
            ]);
        }
    }

}

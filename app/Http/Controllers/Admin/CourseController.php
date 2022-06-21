<?php

namespace App\Http\Controllers\Admin;

use App\course;
use App\faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    private $course;
    public function __construct(course $course)
    {
        $this->course = $course;
    }

    public function index($faculty_id){
        $facultyById = faculty::find($faculty_id);

        return view('admin.course.index', [
            'facultyById'=> $facultyById,

        ]);
    }
    public function fetchAll(Request $request){

        //$courseList = $this->course->where('faculty_id', $request->faculty_id)->get();
        $courseList = faculty::find($request->faculty_id)->courses;
        $output = '';
        if(count($courseList)>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã Bộ Môn</th>
                                    <th>Tên Bộ Môn</th>
                                    <th>Học Phần</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($courseList as $item){

                $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$item->course_code.'</td>
                                    <td>'.$item->course_name.'</td>
                                    <td>
                                        <a href="'.route('subjects.subjectOfCourse', ['course_id'=>$item->id]).'" class="btn btn-success btn-sm">
                                            <i class="fa fa-folder"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editCourseModal">
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
    public function store(CourseRequest $request){
        $data = [
            'course_code'=>$request->course_code,
            'course_name'=>$request->course_name,
            'faculty_id'=>$request->faculty_id,
        ];

        $this->course->create($data);

        return response()->json([
            'status'=>200
        ]);
    }

    public function edit(Request $request){
        $coureById = $this->course::find($request->id);
        return response()->json(
            $coureById
        );
    }

    public function update(Request $request){

        $rules = [
            'course_name'   =>  'required',
            'course_code'   =>  [
                'required',
                Rule::unique('courses', 'course_code')->ignore($request->course_id, 'id')
            ]
        ];

        $messages = [
            'required'  =>  'Chưa nhập :attribute !',
            'unique'    =>  ':attribute đã tồn tại'
        ];
        $attributes = [
            'course_name'   =>  'Tên bộ môn',
            'course_code'   =>  'Mã bộ môn'
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        $validator->validate();

        $coureById = $this->course::find($request->course_id);
        $data = [
            'course_code'=>$request->course_code,
            'course_name'=>$request->course_name,
        ];

        $coureById->update($data);

        return response()->json([
            'status'=>200
        ]);
    }

    public function delete(Request $request){
        try{
            $this->course::find($request->id)->delete();

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
}

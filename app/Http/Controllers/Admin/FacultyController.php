<?php

namespace App\Http\Controllers\Admin;

use App\course;
use App\faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyRequest;
use App\User;
use App\User_course;
use App\User_faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    private $faculty;
    public function __construct(faculty $faculty)
    {
        $this->faculty = $faculty;
    }

    public function index(){
        return view('admin.faculty.index');
    }

    public function fetchAll(){

        if(Auth::user()->utype == 'MASTER')
            $facultyList = $this->faculty::all();
        elseif(Auth::user()->utype == 'FACULTY'){
            $facultyId = User_faculty::all()->where('user_id', Auth::user()->id)->first()->faculty_id;
            $facultyList = $this->faculty->where('id', $facultyId)->get();
        }elseif(Auth::user()->utype == 'COURSE'){
            $courseId = User_course::all()->where('user_id', Auth::user()->id)->first()->course_id;
            $courseById = course::find($courseId);
            $facultyId  = faculty::find($courseById->faculty_id);
            $facultyList = $this->faculty->where('id', $facultyId->id)->get();
        }


        $output = '';
        if(count($facultyList)>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID1</th>
                                    <th>Mã Khoa</th>
                                    <th>Tên Khoa</th>
                                    <th>Bộ Môn</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($facultyList as $item){

                $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$item->faculty_code.'</td>
                                    <td>'.$item->faculty_name.'</td>
                                    <td>
                                        <a href="'.route('courses.index', ['faculty_id'=>$item->id]).'" class="btn btn-success btn-sm">
                                            <i class="fa fa-folder"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editFacultyModal">
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
    public function store(FacultyRequest $request){

        $dataFaculty = [
            'faculty_code'=>$request->faculty_code,
            'faculty_name'=>$request->faculty_name,
        ];
        $this->faculty->create($dataFaculty);
        return response()->json([
            'status'=>200
        ]);
    }

    public function edit(Request $request){
        $facultyById = $this->faculty::find($request->id);
        return response()->json($facultyById);
    }

    public function update(Request $request){
        $rules = [
            'faculty_name'   =>  'required',
            'faculty_code'   =>  [
                'required',
                Rule::unique('faculties', 'faculty_code')->ignore($request->faculty_id, 'id')
            ]
        ];

        $messages = [
            'required'  =>  'Chưa nhập :attribute !',
            'unique'    =>  ':attribute đã tồn tại'
        ];
        $attributes = [
            'faculty_name'   =>  'Tên khoa',
            'faculty_code'   =>  'Mã khoa'
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        $validator->validate();

        $facultyById = $this->faculty::find($request->faculty_id);

        $dataFaculty = [
            'faculty_code'=>$request->faculty_code,
            'faculty_name'=>$request->faculty_name,
        ];
        $facultyById->update($dataFaculty);

        return response()->json([
            'status'=>200
        ]);
    }

    public function delete(Request $request){
        try{
            $this->faculty::find($request->id)->delete();

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

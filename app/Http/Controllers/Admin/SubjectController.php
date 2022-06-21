<?php

namespace App\Http\Controllers\Admin;

use App\course;
use App\Http\Controllers\Controller;
use App\SubjectV2;
use Illuminate\Http\Request;
use App\Subject;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    private $subject;

    public function __construct(SubjectV2 $subject)
    {
        $this->subject = $subject;
    }

    public function subjectOfCourse($courseId){

        $courseById = course::find($courseId);
        return view('admin.subject.subjectOfCourse',[
            'courseById'=>$courseById
        ]);
    }

    public function store(Request $request){
        $data =[
            'subject_name'=>$request->subject_name,
            'subject_code'=>$request->subject_code,
            'course_id' =>  $request->course_id,
            'numCredit' =>  $request->numCredit,
            'subject_status'=>true
        ];

        $this->subject->create($data);

        return response()->json([
            'status'=>200
        ]);
    }
    public function edit(Request $request){

        $subjectById = $this->subject::find($request->id);

        return response()->json($subjectById);
    }

    public function update(Request $request){

        //return response()->json($request->all());
        $subjectById = $this->subject::find($request->subject_id);

        $data =[
            'subject_name'=>$request->subject_name,
            'subject_code'=>$request->subject_code,
            'numCredit' =>  $request->numCredit,
        ];

        $subjectById->update($data);

        return response()->json([
            'status'=>200
        ]);
    }

    public function delete(Request $request){
        try{
            $this->subject::find($request->id)->delete();

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



    public function fetchSubject(Request $request){


        $subjectsOfCoure = course::find($request->course_id)->subjects;


        $output = '';
        if($subjectsOfCoure->count()>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã Học Phần</th>
                                    <th>Tên Học Phần</th>
                                    <th>Số Tín Chỉ</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($subjectsOfCoure as $item){

                $output .='<tr>
                                <td>'.$item->id.'</td>
                                <td>'.$item->subject_code.'</td>
                                <td>'.$item->subject_name.'</td>
                                <td>'.$item->numCredit.'</td>
                                <td>
                                    <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                    data-toggle="modal" data-target="#editSubjectModal">
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
}

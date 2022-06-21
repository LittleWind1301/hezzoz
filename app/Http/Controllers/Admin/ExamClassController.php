<?php

namespace App\Http\Controllers\Admin;

use App\Clazz;
use App\Exam;
use App\Exam_class;
use App\Exam_lecturers;
use App\faculty;
use App\Http\Controllers\Controller;
use App\ProfileStudent;
use App\Student_class;
use Illuminate\Http\Request;

class ExamClassController extends Controller
{
    private $examClass;

    public function __construct(Exam_class $exam_class)
    {
        $this->examClass = $exam_class;
    }

    public function examOfClass($class_id){
        $classById = Clazz::find($class_id);

        return view('admin.class.examClass.listExam', ['classById'=>$classById]);
    }

    public function fetchAll(Request $request){
        $exam_class = $this->examClass::all()->where('classId', $request->class_id);

        $output='';

        if (count($exam_class) > 0) {
            $output .= '<table id="examTable" class="table table-bordered table-sm ">
                            <thead>
                                <tr align="center">
                                    <th>ID</th>
                                    <th>Mã Đề</th>
                                    <th>Tiêu Đề</th>
                                    <th>Thời Gian Làm</th>
                                    <th>Thời Gian Bắt Đầu Thi</th>
                                    <th>Thời Gian Kết Thúc Thi</th>
                                    <th>Số Câu Hỏi</th>
                                    <th>Trạng Thái</th>
                                    <th>Giám Thị</th>
                                    <th>Hành Động</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($exam_class as $item) {
                $exam = Exam::find($item->examId);

                $exam_class_status = '';
                $btnDelete = '';
                $btnViewResult = '';
                $btnPublishResult = '';

                if($item->status == -1){
                    $exam_class_status = 'Đang Đóng';
                    $btnDelete = ' <a href="javascript:;" onclick="deleteItem('.$item->id.')" class="text-danger mx-1">
                                        <i class="bi-trash h4"></i>
                                    </a>';
                }
                elseif ($item->status == 0)
                    $exam_class_status = 'Đang Mở';
                elseif($item->status == 1){
                    $exam_class_status = 'Kết Thúc';
                    $btnViewResult = ' <a href="'.route('resultExams.viewResult', ['exam_class_id'=>$item->id]).'" class="btn btn-info">
                                        Xem Kết Quả
                                    </a>';

                    if($item->publish_result)
                        $btnPublishResult =  ' <a href="javascript:;" class="btn btn-success">
                                            Đã công bố kết quả
                                            </a>';
                    else
                        $btnPublishResult =  ' <a href="javascript:;" onclick="publishResult('.$item->id.')" class="btn btn-primary">
                                            Công bố kết quả
                                            </a>';
                }

                $output .= '<tr>
                                <td>
                                    '.$exam->id.'
                                    <input type="hidden" name="exam_class_id" value="'.$item->id.'">
                                </td>
                                <td>'.$exam->exam_code.'</td>
                                <td>'.$exam->title.'</td>
                                <td align="center">'.$exam->limitTime.'</td>
                                <td align="center">'.$exam->timeStart.'</td>
                                <td align="center">'.$exam->timeFinish.'</td>
                                <td align="center">'.$exam->total_question.'</td>
                                <td>
                                    <button class="btn btn-info">'.$exam_class_status.'</button>
                                </td>
                                <td>
                                    <a href="'.route('lecturersOfExamClass.lecturersOfExamClass', ['exam_class_id'=>$item->id]).'" class="btn btn-success btn-sm">
                                        <i class="fa fa-folder"></i>
                                    </a>
                                </td>
                                <td align="center">'.$btnDelete.'</td>
                                <td align="center">'.$btnViewResult.' <br> <br>  '.$btnPublishResult.'</td>
                            </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function create(Request $request){
        $classById = Clazz::Find($request->class_id);
        $facultyOfClass = faculty::find($classById->faculty_id);

        $listExam = [];
        $courseOfFaculty = $facultyOfClass->courses;

        foreach($courseOfFaculty as $course){
            $subjectOfCourse = $course->subjects;
            foreach($subjectOfCourse as $subject){
                if(count($subject->exams)>0)
                    foreach($subject->exams as $exam){
                        if ($exam->exam_status == 'Đã Xác Nhận')
                            $listExam[] = $exam;
                    }
            }
        }

        $exam_class = Exam_class::all()->where('classId', $request->class_id);

        $listExamOutClass = [];


        foreach ($listExam as $item){

            if(!$exam_class->contains('examId', $item->id)){

                $listExamOutClass[] = $item;
            }
        }



        $output = '';
        if(count($listExamOutClass)>0){
            $output .= '<table id="mytable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>ID</th>
                                    <th>Mã đề</th>
                                    <th>Tiêu đề</th>
                                    <th>Thời gian(phút)</th>
                                    <th>Thời gian bắt đầu</th>
                                    <th>Thời gian kết thúc</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($listExamOutClass as $item) {
                $output .= '<tr>
                                <td>
                                    <input type="checkbox"
                                           class="checkbox_exam"
                                           name="exam_id[]"
                                           value="'.$item->id.'">
                                </td>
                                <td>'.$item->id.'</td>
                                <td>'.$item->exam_code.'</td>
                                <td>'.$item->title.'</td>
                                <td>'.$item->limitTime.'</td>
                                <td>'.$item->timeStart.'</td>
                                <td>'.$item->timeFinish.'</td>
                            </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function store(Request $request)
    {
        foreach ($request->exam_id as $exam_id){
            $this->examClass->create([
                'examId'=>  $exam_id,
                'classId'  =>  $request->class_id
            ]);
        }
        return response()->json([
            'status' => 200
        ]);
    }

    public function delete(Request $request){

        $exam_lecturers = Exam_lecturers::all()->where('exam_class_id', $request->id);
        foreach ($exam_lecturers as $item){
            $item->delete();
        }
        $exam_class = Exam_class::destroy($request->id);
        return response()->json([
            'status'=>200
        ]);
    }

    public function publishResult(Request $request){
        $exam_class = Exam_class::find($request->exam_class_id);
        $exam_class->update([
            'publish_result'=>true
        ]);

        return response()->json([
            'status'=>200
        ]);
    }
}

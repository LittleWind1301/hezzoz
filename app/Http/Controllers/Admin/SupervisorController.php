<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;

use App\Clazz;
use App\course;
use App\Exam;
use App\Exam_class;
use App\Exam_lecturers;
use App\faculty;
use App\Http\Controllers\Controller;
use App\LecturersOfSchedule;
use App\Question;
use App\QuestionOption;
use App\RandomCodeExam;
use App\Schedule;
use App\SubjectV2;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    private $attendance;
    private $lecturersOfSchedule;
    private $randomCodeExam;

    public function __construct(Attendance $attendance, LecturersOfSchedule $lecturersOfSchedule, RandomCodeExam $randomCodeExam)
    {
        $this->attendance = $attendance;
        $this->lecturersOfSchedule = $lecturersOfSchedule;
        $this->randomCodeExam   =   $randomCodeExam;
    }


    public function index(){
        return view('admin.supervisor.index');
    }

    public function fetchAll(){
        $lecturers_schedule = $this->lecturersOfSchedule->where('user_id', Auth::user()->id)->get();

        $listSchedule = [];
        foreach ($lecturers_schedule as $item){

            $schedule = Schedule::find($item->schedule_id);
            if(!$schedule->exam_id == null){
                $class = Clazz::find($schedule->class_id);
                $data = [
                    'schedule' => $schedule,
                    'class'    => $class
                ];
                $listSchedule[] = $data;
            }
        }
        $output = '';
        if(count($listSchedule)>0){
            $output .= '<table id="scheduleTable" class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tiêu đề</th>
                                    <th>Lớp học phần</th>
                                    <th>Thời gian</th>
                                    <th>Thời gian bắt đầu</th>
                                    <th>Thời gian kết thúc</th>
                                    <th>Học kì</th>
                                    <th>Điểm danh</th>
                                    <th>Đề thi</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>';

            foreach ($listSchedule as $item){
                $examStatus = '';
                $btnPDF = '';
                if($item['schedule']->exam_status == -1){
                    $examStatus =
                        '<div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Chưa bắt đầu
                            </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item btn btn-info" href="javascript:;" id="" onclick="startExam('.$item['schedule']->id.')">Bắt đầu thi</a>
                          </div>
                        </div>';
                }elseif ($item['schedule']->exam_status == 0){
                    $examStatus =
                        '<div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Đang thi
                            </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item btn btn-info" href="javascript:;" onclick="finishExam('.$item['schedule']->id.')">Kết thúc</a>
                          </div>
                        </div>';
                }elseif ($item['schedule']->exam_status == 1){
                    $examStatus =
                        '<button class="btn btn-success">Đã kết thúc</button>';

                    $btnPDF =
                        '<a href="'.route('supervisor.generatePDF', ['schedule_id' => $item['schedule']->id]).'" target="_blank" class="btn btn-info">
                            Tải file PDF
                        </a>';
                }


                $output .='<tr>
                                <td>'.$item["schedule"]->id.'</td>
                                <td>'.$item["schedule"]->title.'</td>
                                 <td>'.$item["class"]->class_name.'</td>
                                <td>'.$item["schedule"]->limitTime.' phút</td>
                                <td>'.$item["schedule"]->timeStart.'</td>
                                <td>'.$item["schedule"]->timeFinish.'</td>
                                <td>'.$item["class"]->semester.'</td>
                                <td>
                                  <a href="#"  class="btn btn-info"
                                               data-toggle="modal" data-target="#listAttendances"  onclick="getAttendancesList('.$item["schedule"]->id.')">
                                                Điểm danh
                                  </a>
                                </td>
                                <td>
                                     <a href="'.route('supervisor.viewCodeExam',['exam_id'=>$item["schedule"]->exam_id]).'"  class="btn btn-success btn-sm">
                                        <i class="fa fa-folder"></i>
                                     </a>
                                </td>
                                <td>
                                    '.$examStatus.' <br><br> '.$btnPDF.'
                                </td>

                            </tr>';
            }
            $output .='</tbody></table>';
            echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }

    }

    public function getAttendancesList(Request $request){

        $listAttendance = $this->attendance->where('schedule_id', $request->schedule_id)->get();

        $output = '';
        if(count($listAttendance)>0){
            $output .= '<table id="myTable" class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Mã SV</th>
                                    <th>Họ Tên</th>
                                    <th>Ngày Sinh</th>
                                    <th>Giới Tính</th>
                                    <th>Điểm Danh</th>
                                    <th>Trạng Thái</th>
                                    <th>Kết quả</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($listAttendance as $item){

                $profileStudent = User::find($item->user_id)->profileStudent;
                $email = User::find($item->user_id)->email;
                $submit_status = '';
                $mark = '';
                if($item->submit_status == true){
                    $submit_status = 'Đã Nộp Bài <br> '.$item->submit_time;
                    $mark = $item->mark;
                }
                $output .='<tr>
                                    <td>'.$email.'</td>
                                    <td>'.$profileStudent->student_id.'</td>
                                    <td>'.$profileStudent->student_name.'</td>
                                    <td>'.$profileStudent->dateOfBirth.'</td>
                                    <td>'.$profileStudent->gender.'</td>
                                    <td>'.$item->checkin_time.'</td>
                                    <td>'.$submit_status.'</td>
                                    <td>'.$mark.'</td>
                                </tr>';
            }
            $output .='</tbody></table>';
            echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function startExam(Request $request){
        $schedule = Schedule::find($request->schedule_id);
        $schedule->update([
            'exam_status'=>0
        ]);

        return response()->json([
            'status'=>200
        ]);
    }

    public function finishExam(Request $request){
        $schedule = Schedule::find($request->schedule_id);
        $schedule->update([
            'exam_status'=>1
        ]);

        return response()->json([
            'status'=>200
        ]);
    }

    public function viewCodeExam($exam_id){
        $listCodeExam = $this->randomCodeExam->where('exam_id', $exam_id)->get();
        return view('admin.supervisor.listExamCode', [
            'listCodeExam' =>  $listCodeExam
        ]);
    }

    public function getDetailCodeExam(Request $request){

        $codeExam = $this->randomCodeExam->find($request->code_exam);
        $listQuestion = $codeExam->questions;


        $output = '<div class="alert-info alert"><h4>Chi tiết mã đề số '.$codeExam->codeExam.'</h4></div>';
        if(count($listQuestion)>0){
            $output .=
                '<table id="myTable" class="table-valign-middle table-striped table">
                    <thead>
                        <tr>
                            <th>Câu hỏi</th>
                            <th>Đáp án</th>
                        </tr>
                    </thead><tbody>';
            foreach ($listQuestion as $item){

                $outputOption = '';
                foreach ($item->options as $option){
                    $questionOption = QuestionOption::find($option->optionId);
                    $isCorrect = '';
                    if($option->isCorrect)
                        $isCorrect = '<i class="fa fa-check-circle fa-2x text-success"></i>';
                    $outputOption .=
                        '<div class="row" style="border-bottom: solid 1px ">
                            <div class="col-md-3">Đáp án '.$option->optionNumber.' :</div>
                            <div class="col-md-7"> '.$questionOption->optionTitle.'</div>
                            <div class="col-md-2">'.$isCorrect.'</div>
                        </div>';
                }
                $question = Question::find($item->questionId);
                $output .=
                    '<tr class="" >
                        <td class="">
                            <h5>Câu hỏi số '.$item->questionNumber.' :</h5>
                            <p>'.$question->question_content.'</p>
                        </td>
                       <td>
                           '.$outputOption.'
                        </td>
                    </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function generatePDF($schedule_id){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convertExamResultToHTML($schedule_id));
        return $pdf->stream();
    }

    public function convertExamResultToHTML($schedule_id){
        $scheduleById = Schedule::find($schedule_id);
        $class = Clazz::find($scheduleById->class_id);
        $subject = SubjectV2::find($class->subject_id);
        $course = course::find($subject->course_id);
        $faculty = faculty::find($course->faculty_id);

        $listAttendances = $scheduleById->attendances;

        $output = '';

        $output .=
            '<style>
                body{
                    font-family: "DejaVu Sans";
                }
                .header{
                    width: 100%;
                }
                .header-left{
                    float: left;
                    width: 50%;
                }
                .header-right{
                    float: left;
                    width: 50%;
                }
                .content{
                    clear: left;
                    width: 100%;
                }
                .content-left{
                    float: left;
                    width: 70%;
                }

                .content-right{
                    float: left;
                    width: 30%;
                }
                table{
                    clear: left;
                    width: 100%;

                }
                table, th {
                    border: 1px solid black;
                    border-collapse: collapse;
                }
                td{
                    border-left: 1px solid black;

                    text-align: center;
                }
                tr{
                border-bottom: dotted black;
                }

            </style>

            <div class="header">
                <div class="header-left">
                    <h4><center>TRƯỜNG ĐẠI HỌC GTVT</center></h4>
                    <h5 style="text-decoration: underline"><center>Khoa '.$faculty->faculty_name.'</center></h5>
                </div>
                <div class="header-right">
                    <h4><center>Điểm thi kết thúc học phần</center></h4>
                    <h5><center>Khoá '.$class->courseNumber.' - Học kì '.$class->semester.'</center></h5>
                </div>
            </div>
            <div class="content">
                <div class="content-left">
                    <p>Học phần: '.$subject->subject_name.'</p>
                    <p>Tên lớp học phần: '.$class->class_name.'</p>
                    <p>Mã lớp học phần: '.$class->class_code.'</p>
                </div>
                <div class="content-right">
                    <p><center>Số tín chỉ: '.$subject->numCredit.'</center></p>
                </div>
            </div>

            <table>
                <tr>
                    <th>STT</th>
                    <th>Mã số SV</th>
                    <th>Họ và tên</th>
                    <th>Khoá</th>
                    <th>Nộp bài</th>
                    <th>Điểm</th>
                </tr>';

        $i=1;
        foreach ($listAttendances as $item){

            $student = User::find($item->user_id)->profileStudent;

            $submitStatus = 'Chưa nộp bài';
            $mark = '';
            if($item->submit_status){
                $submitStatus = 'Đã nộp bài <br>'.$item->submit_time;
                $mark = $item->mark;
            }
            $output .=
                '<tr>
                    <td>'.$i.'</td>
                    <td>'.$student->student_id.'</td>
                     <td>'.$student->student_name.'</td>
                     <td>K'.$student->yearOfAdmission.'</td>
                     <td>'.$submitStatus.'</td>
                     <td>'.$mark.'</td>
                </tr>';

            $i++;
        }
        $output .= '</table>';

        return $output;
    }



    public function generateCodeExamPDF($codeExamId){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convertCodeExamToHTML($codeExamId));
        return $pdf->stream();
    }

    public function convertCodeExamToHTML($codeExamId){
        $codeExam = $this->randomCodeExam->find($codeExamId);
        $exam = Exam::find($codeExam->exam_id);
        $subject = SubjectV2::find($exam->subjectId);
        $course = course::find($subject->course_id);
        $faculty = faculty::find($course->faculty_id);

        $listQuestion = $codeExam->questions;

        $output =
            '<style>
                body{
                    font-family: "DejaVu Sans";
                }
                .header{
                    width: 100%;
                }
                .header-left{
                    float: left;
                    width: 50%;
                }
                .header-right{
                    float: left;
                    width: 50%;
                }
                .nav{
                    clear: left;
                    width: 100%;
                }
                .nav-left{
                    float: left;
                    width: 60%;
                }
                .nav-right{
                    float: left;
                    width: 50%;
                }
                .content{
                    clear: left;
                    width: 100%;
                }
                .question{
                    clear: left;
                    width: 100%;
                }
                .question-left{
                    float: left;
                    width: 10%;
                }
                .question-right{
                    float: left;
                    width: 90%;
                }
                .option{
                    clear: left;
                    width: 100%;
                }
                .option-left{
                    float: left;
                    width: 3%;
                }
                .option-right{
                    float: left;
                    width: 90%;
                }



            </style>

            <div class="header">
                <div class="header-left">
                    <h4><center>TRƯỜNG ĐẠI HỌC GTVT</center></h4>
                    <h5 style="text-decoration: underline"><center>Khoa '.$faculty->faculty_name.'</center></h5>
                </div>
                <div class="header-right">
                    <h4><center>Đề thi trắc nghiệm</center></h4>
                    <h5><center>Học phần: '.$subject->subject_name.'</center></h5>
                    <h5><center>Bài thi: '.$exam->title.'</center></h5>
                </div>
            </div>
            <div class="nav">
                <div class="nav-left">
                    <p>Họ Và Tên: ...........................................</p>
                    <p>Mã SV: ..................................................</p>
                </div>
                <div class="nav-right">
                    <p style="border: 1px solid black; padding: 8px; width: 90px">Mã Đề : '.$codeExam->codeExam.'</p>
                </div>
            </div>
            <div class="content">';

        foreach ($listQuestion as $item) {
            $question = Question::find($item->questionId);

            $outputOption='';
            foreach ($item->options as $option){
                $questionOption = QuestionOption::find($option->optionId);
                $outputOption .=
                    '<div class="option" style="margin:10px 0 20px 30px; padding: 10px">
                        <div class="option-left">'.$option->optionNumber.'.</div>
                        <div class="option-right">  '.$questionOption->optionTitle.'</div>
                    </div>';
            }

            $output .=
                '<div>
                    <div class="question" style="margin-top: 15px">
                        <div class="question-left">Câu '.$item->questionNumber.'.</div>
                        <div class="question-right">  '.$question->question_content.'</div>
                    </div>
                    '.$outputOption.'
                </div>
                ';
        }

            $output .= '</div>';



        return $output;
    }


}

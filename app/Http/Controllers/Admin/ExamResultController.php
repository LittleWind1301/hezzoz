<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Clazz;
use App\course;
use App\Exam;
use App\Exam_class;
use App\faculty;
use App\Http\Controllers\Controller;
use App\Question;
use App\QuestionOption;
use App\RandomCodeExam;
use App\Schedule;
use App\StudentAnswer;
use App\SubjectV2;
use App\User;
use http\Env\Response;
use Illuminate\Support\Facades\App;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamResultController extends Controller
{
    private $studentAnswer;
    private $attendance;
     public function __construct(StudentAnswer $studentAnswer, Attendance $attendance)
     {
         $this->studentAnswer = $studentAnswer;
         $this->attendance = $attendance;
     }

     public function index($schedule_id){

         $scheduleById = Schedule::find($schedule_id);
         return view('admin.class.schedule.examResult.index', [
             'scheduleById'    =>  $scheduleById
         ]);
     }

    public function fetchAll(Request $request)
    {
        $scheduleById = Schedule::find($request->schedule_id);
        $listAttendances = $scheduleById->attendances;


        $output = '';

        if (count($listAttendances) > 0) {
            $output .= '<table id="tableExamResult" class="table table-hover table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã SV</th>
                                    <th>Tên Sinh Viên</th>
                                    <th>Ngày Sinh</th>
                                    <th>Giới Tính</th>
                                    <th>Khoá</th>
                                    <th>Điểm danh</th>
                                    <th>Nộp bài</th>
                                    <th>Điểm</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>';

            $i=0;
            foreach ($listAttendances as $item) {
                $i++;

                $student = User::find($item->user_id)->profileStudent;

                $submitStatus = '';
                $mark = '';
                $detailResult = '';
                if($item->submit_status){
                    $submitStatus = 'Đã nộp bài<br> '.$item->submit_time;
                    $mark = $item->mark;
                    $detailResult =
                        ' <a href="'.route('resultExams.detailExamResult', ['schedule_id'=>$request->schedule_id, 'user_id'=>$item->user_id]).'" class="btn btn-info btn-sm">
                            Xem chi tiết
                        </a>';
                }else{
                    $submitStatus = 'Chưa nộp bài';
                }
                $output .= '<tr>
                                <td>'.$i.'</td>
                                <td>'.$student->student_id.'</td>
                                <td>'.$student->student_name.'</td>
                                <td>'.$student->dateOfBirth.'</td>
                                <td>'.$student->gender.'</td>
                                <td>K'.$student->yearOfAdmission.'</td>
                                <td>'.$item->checkin_time.'</td>
                                <td>'.$submitStatus.'</td>
                                <td>'.$mark.'</td>
                                <td>'.$detailResult.'</td>
                            </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function detailExamResult($schedule_id, $user_id){
         $student = User::find($user_id)->profileStudent;

         $attendance = $this->attendance->where([
             'schedule_id'  =>  $schedule_id,
             'user_id'      =>  $user_id
         ])->first();

         $studentAnswer = $this->studentAnswer->where('attendance_id', $attendance->id)->get();

         $codeExam = RandomCodeExam::find($attendance->codeExamId);
         $exam = Exam::find($codeExam->exam_id);
         $listQuestion = $codeExam->questions;

         $codeExamData = [];

         foreach ($listQuestion as $item){
             $checkCorrectFlag = true;
             $question = Question::find($item->questionId);
             $options = $item->options;

             $dataOption = [];
             $dataAnswer = [];
             $correctAnswer = [];

             $countCorrect = 0;
             $countStudentCorrectAnswer = 0;
             foreach ($options as $option){
                 $optionTitle = QuestionOption::find($option->optionId)->optionTitle;

                 if (QuestionOption::find($option->optionId)->isCorrect){
                     $countCorrect++;
                     $correctAnswer[] =  [
                         'optionTitle'  =>  $optionTitle,
                         'optionNumber' =>  $option->optionNumber,
                     ];
                 }
                 $dataOption[] =  [
                     'optionTitle'  =>  $optionTitle,
                     'optionNumber' =>  $option->optionNumber,
                 ];


                 foreach ($studentAnswer as $answer){
                     if($answer->questionId == $item->questionId && $answer->answerOption == $option->optionId){
                         if (!QuestionOption::find($answer->answerOption)->isCorrect){
                             $checkCorrectFlag = false;

                         }else{
                             $countStudentCorrectAnswer++;
                         }
                         $dataAnswer[] = [
                             'optionTitle'  =>  $optionTitle,
                             'optionNumber' =>  $option->optionNumber
                         ];
                     }
                 }
             }

             if($checkCorrectFlag==true){
                 if(!$countStudentCorrectAnswer == $countCorrect){
                     $checkCorrectFlag=false;

                 }

             }

             $data = [
                 'questionNumber'   =>   $item->questionNumber,
                 'questionTitle' =>  $question->question_content,
                 'option'   =>  $dataOption,
                 'dataAnswer'  =>  $dataAnswer,
                 'correctAnswer'    =>  $correctAnswer,
                 'checkCorrectFlag'    =>  $checkCorrectFlag
             ];
             $codeExamData[]     = $data;
         }
         return view('admin.class.schedule.examResult.detailResult', [
             'codeExam'    =>  $codeExam,
             'student'     =>  $student,
             'codeExamData'    =>  $codeExamData,
             'exam'        =>  $exam,
             'attendance'  =>  $attendance
         ]);
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










    public function exportPDF($exam_class_id){

        $exam_class = Exam_class::find($exam_class_id);
        $class = Clazz::find($exam_class->classId);
        $faculty = faculty::find($class->faculty_id);
        $exam = Exam::find($exam_class->examId);
        $subject = $exam->subject;
        $listCheckin = Attendance::all()->where('exam_class_id', $exam_class_id);
        $listStudentSubmitExam = [];

        foreach ($listCheckin as $item){
            $user = User::find($item->user_id);
            $user_profile = $user->profileStudent;
            $listStudentSubmitExam[] = [
                'user'=>$user,
                'student_profile'=>$user_profile,
                'attendance'=>$item
            ];
        }
        $pdf = PDF::loadView('admin.class.examClass.examResult.pdf', [
            'listStudentSubmitExam'=>$listStudentSubmitExam,
            'class'=>$class,
            'exam'=>$exam,
            'subject'=>$subject,
            'faculty'=>$faculty,
            'exam_class_id'=>$exam_class_id
        ]);
        return $pdf->stream('ket_qua.pdf');
    }


    public function viewStudentAnswer($exam_class_id, $user_id){

        $exam_class = Exam_class::find($exam_class_id);

        $examById = Exam::find($exam_class->examId);

        $listQuestion = $examById->questions;

        $detailAnswer = [];

        foreach ($listQuestion as $question){
            $student_answer = $this->studentAnswer
                ->where('exam_class_id', $exam_class_id)
                ->where('studentId', $user_id)
                ->where('questionId', $question->id)
                ->first()->answerOption;

            $detailAnswer[] = [

                'question'=>$question,
                'option'=>$question->questionOptions,
                'studentAnswer'=>$student_answer
            ];
        }

         return view('admin.class.examClass.examResult.studentAnswer', [
             'detailAnswer'=>$detailAnswer,
             'profileStudent'=>User::find($user_id)->profileStudent,
         ]);
    }

}

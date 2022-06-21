<?php

namespace App\Http\Controllers\Student;

use App\Attendance;
use App\Clazz;
use App\Exam;
use App\Exam_class;
use App\Http\Controllers\Controller;
use App\Question;
use App\QuestionOption;
use App\RandomCodeExam;
use App\Schedule;
use App\StudentAnswer;
use App\Subject;
use App\SubjectV2;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private $student;
    private $attendance;
    private $studentAnswer;
    private $questionOption;

    public function __construct(User $student, StudentAnswer $studentAnswer, Attendance $attendance, QuestionOption $questionOption)
    {
        $this->student = $student;
        $this->studentAnswer = $studentAnswer;
        $this->attendance = $attendance;
        $this->questionOption=$questionOption;
    }

    public function index(){
        return view('student.home');
    }

    public function fetchAll(){
        $studentById = $this->student::find(Auth::user()->id);
        $classesOfStudent = $studentById->classes;
        $allSchedule = [];

        foreach ($classesOfStudent as $class){
            $listSchedule = $class->schedules;
            foreach ($listSchedule as $schedule){
                $checkin = Attendance::where([
                    'user_id' => Auth::user()->id,
                    'schedule_id'   =>  $schedule->id
                ])->first();

                $submit_status = false;
                if($checkin != null ){
                    $checkin_status = true;
                    if ($checkin->submit_status)
                        $submit_status = true;
                }
                else
                    $checkin_status = false;

                $allSchedule[] = [
                    'schedule'         =>  $schedule,
                    'class'             =>  $class,
                    'checkin_status'   =>  $checkin_status,
                    'submit_status'    =>  $submit_status
                ];
            }
        }

        $output = '';
        if(count($allSchedule)>0){
            $output .= '<table id="mytable" class="table table-striped table-sm text-center align-middle">
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
                                    <th>Trạng thái</th>
                                    <th>Kết quả</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($allSchedule as $item) {
                $btnCheckin = '';
                if($item['checkin_status'])
                    $btnCheckin = '<button class="btn btn-success">Đã điểm danh</button>';
                else
                    $btnCheckin = '<a href="javascript:;" id="" onclick="checkin('.$item["schedule"]->id.')" class="btn btn-warning">
                                        Chưa điểm danh
                                    </a>';

                $btnExamStatus = '';
                if($item["schedule"]->exam_status == -1)
                    $btnExamStatus =
                        '<button class="btn btn-primary">Chưa bắt đầu</button>';
                else if($item["schedule"]->exam_status == 0 && $item['checkin_status'] && !$item["submit_status"])
                    $btnExamStatus =
                        '<a href="javascript:;" onclick="getDetailInfoExam('.$item["schedule"]->id.')" class="btn b btn-info" data-toggle="modal" data-target="#examInfo">
                            Xem
                        </a>';

                else if($item["schedule"]->exam_status == 1)
                    $btnExamStatus =
                        '<button class="btn btn-danger">Đã kết thúc</button>';

                $viewResult = '';
                if($item["submit_status"]){
                    $btnExamStatus =  '<button class="btn btn-danger">Đã kết thúc</button>';

                    $viewResult =
                        '<a href="'.route('homeStudents.viewResult', ['schedule_id'=>$item["schedule"]->id]).'" class="btn  btn-info">
                            Xem kết quả
                        </a>';
                }
                $output .= '<tr>
                                <td>'.$item["schedule"]->id.'</td>
                                <td>'.$item["schedule"]->title.'</td>
                                <td>'.$item["class"]->class_name.'</td>
                                <td>'.$item["schedule"]->limitTime.' phút</td>
                                <td>'.$item["schedule"]->timeStart.'</td>
                                <td>'.$item["schedule"]->timeFinish.'</td>
                                <td>'.$item["class"]->semester.'</td>
                                <td>'.$btnCheckin.'</td>
                                <td>'.$btnExamStatus.'</td>
                                <td>'.$viewResult.'</td>
                            </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function checkin(Request $request){

        $schedule = Schedule::find($request->schedule_id);
        $exam = Exam::find($schedule->exam_id);
        $listCodeExam = $exam->randomCodeExams;

        $listCodeExamId = [];
        foreach ($listCodeExam as $codeExam){
            $listCodeExamId[] = $codeExam->id;
        }
        shuffle($listCodeExamId);
        $this->attendance->create([
            'schedule_id' => $request->schedule_id,
            'user_id'       =>  Auth::user()->id,
            'checkin_time'  =>  Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
            'codeExamId'    =>  $listCodeExamId[0],
            'submit_status' => false,
        ]);


        return response()->json([
            'status'=>200
        ]);
    }

    public function detailExam($attendance_id){
        $attendance = Attendance::find($attendance_id);

        if($attendance->openExamTime == null){
            $attendance->update([
                'openExamTime'  =>  Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString()
            ]);
        }

        $schedule = Schedule::find($attendance->schedule_id);
        $timeCloseExam = Carbon::parse($attendance->openExamTime)->addMinute($schedule->limitTime);

        return view('student.detail_exam', [
            'codeExamId'=>$attendance->codeExamId,
            'attendance_id' => $attendance_id,
            'timeCloseExam'    =>  $timeCloseExam->isoFormat('HH:mm:ss  DD/MM/YYYY')
        ]);
    }

    public function fetchDetailExam(Request $request){
        $codeExam = RandomCodeExam::find($request->codeExamId);

        $listQuestion = $codeExam->questions;

        $output = '';
        if(count($listQuestion)>0){
            foreach ($listQuestion as $item){
                $options = $item->options;

                $countCorrectOption = 0;
                foreach ($options as $option){
                    if ($option->isCorrect)
                        $countCorrectOption++;
                }

                $optionHTML = '';
                foreach ($options as $option){
                    $questionOption = QuestionOption::find($option->optionId);
                    if($countCorrectOption  >1){
                        $optionHTML .=
                            '<div class="form-check ">
                                <input class="form-check-input" name="question_'.$item->questionId.'[]" type="checkbox" value="'.$questionOption->id.'">
                                <label class="form-check-label">'.$questionOption->optionTitle.'</label>
                            </div>';
                    }else if ($countCorrectOption == 1){
                        $optionHTML .=
                            '<div class="form-check ">
                                 <input class="form-check-input" name="question_'.$item->questionId.'[]" type="radio" value="'.$questionOption->id.'">
                                <label class="form-check-label">'.$questionOption->optionTitle.'</label>
                            </div>';
                    }

                }
                $question = Question::find($item->questionId);
                $output .=
                    '<div class="shadow-lg p-3 mb-5 bg-white rounded">

                        <div class="row" style="border-bottom: solid 1px">
                            <div class="col-md-1">Câu hỏi số '.$item->questionNumber.'</div>
                            <div class="col-md-10">'.$question->question_content.'</div>
                        </div>
                        <div>
                            '.$optionHTML.'
                        </div>
                    </div>';
            }
            echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }


    public function examInfo(Request $request){

        $schedule = Schedule::find($request->schedule_id);

        $exam = Exam::find($schedule->exam_id);

        $attendances =$this->attendance->where([
            'user_id'   =>  Auth::user()->id,
            'schedule_id'   =>  $request->schedule_id
        ])->first();

        $codeExam = RandomCodeExam::find($attendances->codeExamId);


        return response()->json([
            'exam' =>  $exam,
            'codeExam' =>  $codeExam,
            'attendances'  =>  $attendances
        ]);

    }

    public function postAnswer(Request $request){

        $attendance = $this->attendance::find($request->attendance_id);

        $schedule = Schedule::find($attendance->schedule_id);

        $submitStatus = $attendance->submit_status;
        if(!$submitStatus && $schedule->exam_status == 0){
            try{
                DB::beginTransaction();
                $codeExam = RandomCodeExam::find($attendance->codeExamId);

                $listQuestion = $codeExam->questions;

                $totalQuestion = 0;
                $totalCorrectAnswer = 0;
                foreach ($listQuestion as $question){
                    $correctAnswer = $this->questionOption->where([
                        'questionId'    =>  $question->questionId,
                        'isCorrect'     =>  true
                    ])->get();

                    $totalCorrect = count($correctAnswer);

                    $key = 'question_'.$question->questionId;

                    $countCorrectStudentAnswer = 0;
                    $checkCorrect = true;

                    if (!array_key_exists($key, $request->all())) {
                        $this->studentAnswer->create([
                            'questionId'    =>  $question->questionId,
                            'answerOption'  =>  -1,
                            'attendance_id' =>  $request->attendance_id
                        ]);
                        $checkCorrect = false;
                    }else{
                        foreach ($request->$key as $item){
                            $this->studentAnswer->create([
                                'questionId'    =>  $question->questionId,
                                'answerOption'  =>  $item,
                                'attendance_id' =>  $request->attendance_id
                            ]);
                            $isCorrect = $this->questionOption->find($item)->isCorrect;
                            if(!$isCorrect)
                                $checkCorrect = false;
                            if($isCorrect)
                                $countCorrectStudentAnswer++;
                        }
                    }


                    if($totalCorrect == $countCorrectStudentAnswer && $checkCorrect)
                        $totalCorrectAnswer++;

                    $totalQuestion++;
                }

                $exam = Exam::find($schedule->exam_id)->maxPoint;
                $mark = $totalCorrectAnswer/$totalQuestion *  $exam;

                $attendance->update([
                    'submit_status'=>true,
                    'submit_time'=>Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
                    'mark'=>$mark
                ]);

                DB::commit();
                return  response()->json([
                    'status'    =>  200,
                    'messages'  =>  'Nộp bài thành công',
                    'schedule_id'   =>  $attendance->schedule_id
                ]);
            }catch (\Exception $exception){
                DB::rollBack();
                return response()->json([
                    'status'    =>  `500`,
                    'messages'  =>  $exception->getMessage()
                ]);
            }

        }else{
            return response()->json([
                'status'    =>  0,
                'messages'  =>  'Bài thi đã kết thúc hoặc bạn đã nộp bài rồi!'
            ]);
        }
    }



    public function viewResult($schedule_id){
        $user_id = Auth::user()->id;
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
        return view('student.studentAnswer', [
            'codeExam'    =>  $codeExam,
            'student'     =>  $student,
            'codeExamData'    =>  $codeExamData,
            'exam'        =>  $exam,
            'attendance'  =>  $attendance
        ]);
    }
}

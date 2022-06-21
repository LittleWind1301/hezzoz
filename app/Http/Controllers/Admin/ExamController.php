<?php

namespace App\Http\Controllers\Admin;

use App\Clazz;
use App\course;
use App\faculty;
use App\Http\Controllers\Controller;
use App\OptionOfCodeExam;
use App\Question;
use App\QuestionOfCodeExam;
use App\QuestionOption;
use App\RandomCodeExam;
use App\Schedule;
use App\SubjectV2;
use App\User;
use App\User_course;
use App\User_faculty;
use Illuminate\Http\Request;
use App\Exam;
use App\GroupQuestion;
use App\Subject;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ExamController extends Controller
{
    private $exam;
    private $randomCodeExam;
    private $questionOfCodeExam;
    private $optionOfCodeExam;

    public function __construct(Exam $exam, RandomCodeExam $randomCodeExam, QuestionOfCodeExam $questionOfCodeExam, OptionOfCodeExam  $optionOfCodeExam)
    {
        $this->exam = $exam;
        $this->randomCodeExam = $randomCodeExam;
        $this->questionOfCodeExam = $questionOfCodeExam;
        $this->optionOfCodeExam =   $optionOfCodeExam;
    }

    public function listFaculty(){

        $courseId = '';

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

        return view('admin.exam.listFaculty', [
            'listFaculty'=>$listFaculty,
            'courseId' => $courseId
        ]);
    }


    public function examsOfSubject($subject_id){
        $subjectById = SubjectV2::find($subject_id);

        $examOfSubject = $subjectById->exams;

        return view('admin.exam.listExam',[
            'subjectById'=>$subjectById,
            'exams'=>$examOfSubject
        ]);
    }
    public function create($subject_id){
        $subjectById = SubjectV2::find($subject_id);

//        foreach ($subjectById->groupQuestions as $item){
//            dd($item);
//        }
       //dd($subjectById->groupQuestions);
        return view('admin.exam.add', [
            'listGrQuestion'=>$subjectById->groupQuestions,
            'subjectById'=>$subjectById
        ]);
    }

    public function randomQuestion(Request $request){
        foreach ($request->numQues as $item){
            if($item <=0 )
                return response()->json([
                    'status'    =>  0,
                    'messages'  =>  'Mỗi nhóm phải có ít nhất 1 câu hỏi'
                ]);
        }
        try {
            $output = '';
            $output .= '<table class="table table-bordered table-sm ">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Nhóm Câu Hỏi</th>
                                <th>Nội Dung</th>
                                <th>Mức Độ</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>';
            for($i=0; $i<count($request->groupQues); $i++){

                $questions = GroupQuestion::find($request->groupQues[$i])->questions
                    //->inRandomOrder()->limit($request->numQues[$i])->get();
                    ->random($request->numQues[$i]);

                $groupQuesName = GroupQuestion::find($request->groupQues[$i])->name;

                foreach($questions as $item){
                    $output .='<tr id='.$item->id.'>
                                <td>
                                <input type=hidden name="listQuestId[]" value = "'.$item->id.'">
                                '.$item->id.'
                                </td>
                                <td>'.$groupQuesName.'</td>
                                <td>'.$item->question_content.'</td>
                                <td>'.$item->level.'</td>
                                <td>
                                    <a href="javascript:;"  onclick="deleteItem('.$item->id.')" class="text-danger mx-1 deleteIcon">
                                        <i class="bi-trash h4"></i>
                                    </a>
                                </td>
                            </tr>';
                }
            }
            $output .='</tbody></table>';
            return $output;
        }catch (\Exception $ex){
            return response()->json([
                'status'==500,
                'messages'  =>  $ex->getMessage()
            ]);
        }
    }


    public function store(Request $request){
        try {
            DB::beginTransaction();
            $createExam = $this->exam->create([
                'exam_id'     =>  $request->exam_id,
                'title'         =>  $request->title,
                'exam_status'   =>  '',
                'subjectId'     =>  $request->subject_id,
                'total_question'=>  count($request->listQuestId),
                'description'   =>  $request->description,
                'creator'       =>  Auth::user()->email,
                'semester'      =>  $request->semester,
                'maxPoint'      =>  $request->maxPoint,
                'numberOfCodeExam'  =>  $request->numberOfCodeExam
            ]);
            $createExam->questions()->attach($request->listQuestId);


            $randomCodeExam = $this->randomCodeExam($createExam->id, $request->numberOfCodeExam, $request->listQuestId);

            if(!$randomCodeExam){
                DB::rollBack();
                return redirect()->back()->with('error', $randomCodeExam);
            }

            DB::commit();
            $subjectById = SubjectV2::find($request->subject_id);
            return redirect()->route('exams.examsOfSubject', ['subject_id'=>$subjectById])
                                ->with('success', 'Tạo bài thi thành công!');
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function edit($id){
        $examById = $this->exam::find($id);
        $questions = $examById->questions;
        $subjectById = SubjectV2::find($examById->subjectId);
        return view('admin.exam.edit', [
            'examById'=>$examById,
            'questions'=>$questions,
            'subjectById'=>$subjectById,
            'listGrQuestion'=>$subjectById->groupQuestions,
        ]);
    }

    public function update(Request $request, $id){
        try {
            DB::beginTransaction();
            $examById = $this->exam::find($id);
            $dataExam = [
                'exam_id'     =>  $request->exam_id,
                'title'         =>  $request->title,
                'total_question'=>  count($request->listQuestId),
                'description'   =>  $request->description,
                'creator'       =>  Auth::user()->email,
                'semester'      =>  $request->semester,
                'maxPoint'      =>  $request->maxPoint,
                'numberOfCodeExam'  =>  $request->numberOfCodeExam
            ];

            $examById->update($dataExam);
            $examById->questions()->sync($request->listQuestId);

            $subjectById = SubjectV2::find($request->subject_id);

            DB::commit();
            return redirect()->route('exams.examsOfSubject', ['subject_id'=>$subjectById])
                ->with('success', 'Cập nhật bài thi thành công!');
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('error', $ex->getMessage());
        }


    }

    public function delete(Request $request){
        try{
            $this->exam::find($request->id)->delete();

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


    public function randomCodeExam($exam_id, $quality, $listQuestionId){
        try{
            DB::beginTransaction();

            $totalQuestion = count($listQuestionId);
            for($i=1; $i<=$quality; $i++){
                $randomCode = $this->randomCodeExam->create([
                    'exam_id'    =>  $exam_id,
                    'codeExam'  =>  $i,
                ]);
                shuffle($listQuestionId);

                $questionNumber = 1;
                foreach ($listQuestionId as $questionId){
                    $question = $this->questionOfCodeExam->create([
                        'codeExamId'    =>  $randomCode->id,
                        'questionNumber'    =>  $questionNumber,
                        'questionId'    =>  $questionId
                    ]);

                    $questionOptions = Question::find($questionId)->questionOptions;

                    $listOptionId = [];
                    foreach ($questionOptions as $option){
                        $listOptionId[] = $option->id;
                    }
                    shuffle($listOptionId);

                    $optionNumber = 1;
                    foreach ($listOptionId as $optionId){
                        $option = QuestionOption::find($optionId);

                        $this->optionOfCodeExam->create([
                            'questionOfCodeExamId'  =>  $question->id,
                            'optionNumber'  =>  $optionNumber,
                            'isCorrect'     =>  $option->isCorrect,
                            'optionId'      =>  $optionId
                        ]);
                        $optionNumber++;
                    }
                    $questionNumber++;
                }
            }

            DB::commit();
            return true;
        }catch (\Exception $ex){
            DB::rollBack();
            return $ex->getMessage();
        }

    }

    public function viewCodeExam($exam_id){
        $listCodeExam = $this->randomCodeExam->where('exam_id', $exam_id)->get();
        return view('admin.exam.codeExam.listExamCode', [
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\course;
use App\faculty;
use App\GroupQuestion;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\User;
use App\User_course;
use App\User_faculty;
use Illuminate\Http\Request;
use App\Question;
use App\QuestionOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    private $question;
    private $question_option;

    public function __construct(Question $question, QuestionOption $question_option)
    {
        $this->question = $question;
        $this->question_option = $question_option;
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

        return view('admin.question_store.listFaculty', [
            'listFaculty'=>$listFaculty,
            'courseId' => $courseId
        ]);
    }

    public function courseOfFaculty($faculty_id){

        if(Auth::user()->utype == 'MASTER')
            $listCourse = faculty::find($faculty_id)->courses;
        elseif(Auth::user()->utype == 'FACULTY'){
            $listCourse = faculty::find($faculty_id)->courses;
        }elseif(Auth::user()->utype == 'COURSE'){
            $courseId = User_course::all()->where('user_id', Auth::user()->id)->first()->course_id;
            $listCourse = course::all()->where('id', $courseId);

        }


        return view('admin.question_store.listCourse', [
            'listCourse'=>$listCourse]);
    }

    public function questionOfGroup($groupQuest_id){
        $groupQuestion = GroupQuestion::find($groupQuest_id);

        return view('admin.question_store.listQuestion', [
            'groupQuestion'=>$groupQuestion
        ]);
    }

    public function fetchAll(Request $request){
        $groupQuestion = GroupQuestion::find($request->groupQuestId);

        $listQuestionOfGroup =  $groupQuestion->questions;

        $listQuestion = [];
        foreach ($listQuestionOfGroup as $item){
            $item->questionOptions;
            $listQuestion[] = $item;
        }

        $output = '';
        if(count($listQuestion)>0){
            $output .= '<table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>M?? C??u H???i</th>
                                    <th>N???i Dung C??u H???i</th>
                                    <th>C??u Tr??? L???i</th>
                                    <th>M???c ?????</th>
                                    <th>Ng?????i T???o</th>
                                    <th>H??nh ?????ng</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($listQuestion as $item){

                $creator = User::find($item->created_by);
                $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$item->question_content.'</td>
                                    <td>';


                    foreach ($item->questionOptions as $option){
                        $correct = '';
                        if($option->isCorrect == true){
                            $correct = '<i class="fa fa-check col-md-2 text-success">????p ??n-'.$option->optionNumber.'</i>';
                        }
                        $output .= '<div class="row">
                                        <h6 class="col-md-2">????p ??n '.$option->optionNumber.':</h6>
                                        <h6 class="col-md-8">'.$option->optionTitle.' </h6>
                                        '.$correct.'
                                    </div>';
                    }

                $output .=       '</td>
                                    <td>'.$item->level.'</td>
                                    <td>'.$creator->email.'</td>
                                    <td>
                                        <a href="'.route('questions.edit', ['id'=>$item->id]).'" class="text-success mx-1 editIcon">
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
            echo '<h1 class="text-center text-secondary my-5">Kh??ng c?? b???n ghi</h1>';
        }
    }


    public function create($groupQuestId){
        $groupQuestion = GroupQuestion::find($groupQuestId);
        return view('admin.question_store.create',[
            'groupQuestion'=>$groupQuestion
        ]);
    }
    public function store(QuestionRequest $request){

        try {
            DB::beginTransaction();
            $createQuestion = $this->question->create([
                'question_content'    => $request->question_content,
                'group_question_id' => $request->group_question_id,
                'level'             => $request->level,
                'created_by'        => Auth::user()->id
            ]);
            $questionById = $this->question::find($createQuestion->id);

            $i = 1;
            foreach($request->option_title as $optionTitle){
                if(in_array($i, $request->correct)){
                    $dataOption = [
                        'optionNumber'  =>  $i,
                        'optionTitle'   =>  $optionTitle,
                        'isCorrect'     =>  true
                    ];
                }else{
                    $dataOption = [
                        'optionNumber'  =>  $i,
                        'optionTitle'   =>  $optionTitle,
                    ];
                }

                $questionById->questionOptions()->create($dataOption);
                $i++;
            }

            $this->updateNumQuestion($request->group_question_id);

            DB::commit();
            return redirect()
                ->route('questions.questionOfGroup', ['groupQuestionId'=>$request->group_question_id])
                ->with('storeSuccess', 'Th??m c??u h???i th??nh c??ng');

        }catch (\Exception $ex){
            DB::rollBack();
            return back()->with('msg', 'sai roi');
        }

    }

    public function edit($id){
        $questionById = $this->question::find($id);

        $questionById['option'] = $questionById->questionOptions;

        $groupQuestion = GroupQuestion::find($questionById->group_question_id);

        //dd($questionById->option);
        return view('admin.question_store.edit', [
            'questionById'  =>  $questionById,
            'groupQuestion'=>  $groupQuestion
        ]);
    }

    public function update(QuestionRequest $request, $id){
        try {
            DB::beginTransaction();
            $questionById = $this->question::find($id);
            $questionById->update([
                'question_content'  => $request->question_content,
                'level'             => $request->level,
            ]);

            foreach($questionById->questionOptions as $option){
                $option->delete();
            }
            $i = 1;
            foreach($request->option_title as $optionTitle){
                if(in_array($i, $request->correct)){
                    $dataOption = [
                        'optionNumber'  =>  $i,
                        'optionTitle'   =>  $optionTitle,
                        'isCorrect'     =>  true
                    ];
                }else{
                    $dataOption = [
                        'optionNumber'  =>  $i,
                        'optionTitle'   =>  $optionTitle,
                    ];
                }
                $questionById->questionOptions()->create($dataOption);
                $i++;
            }
            DB::commit();
            return redirect()
                ->route('questions.questionOfGroup', ['groupQuestionId'=>$request->group_question_id])
                ->with('updateSuccess', 'C???p nh???t d??? li???u th??nh c??ng');

        }catch (\Exception $ex){
            DB::rollBack();
            return back()->with('fails', 'C?? l???i x???y ra!');
        }
    }

    public function delete(Request $request){
        try{
            $questionById = $this->question::find($request->id);

            $questionById->delete();

            $this->updateNumQuestion($questionById->group_question_id);

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

    public function updateNumQuestion($grQuestionId){
        $groupQuestion = GroupQuestion::find($grQuestionId);

        $groupQuestion->update([
            'numQuestion'=>count($groupQuestion->questions)
        ]);
    }

    public function storeImport(Request $request){
        $rules = [
            'file'=>'required|max:5000|mimes:xlsx, xls, csv'
        ];
        $messages = [
            'file.required'=>'Ch??a th??m file',
            'file.mimes'=>'file kh??ng h???p l???'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->validate();

        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray([], $path);

        foreach ($data as $key => $value){
            unset($value[0]);
            if(count($value)>0){
                foreach ($value as $row){
                    if(!empty($row[0])){
                        try {
                            DB::beginTransaction();

                            $createQuestion = $this->question->create([
                                'question_content'  => $row[0],
                                'group_question_id' => $request->group_question_id,
                                'level'             => $row[1],
                                'created_by'        => Auth::user()->id
                            ]);
                            $questionById = $this->question::find($createQuestion->id);

                            $correctAnswer = explode("," ,$row[8]);

                            for($i=0; $i<6; $i++){
                                if( !empty($row[$i+2]) ){
                                    $isCorrect = false;
                                    if(in_array($i+1, $correctAnswer)){
                                        $isCorrect = true;
                                    }

                                    $questionById->questionOptions()->create([
                                        'optionNumber'  =>  $i+1,
                                        'optionTitle'   =>  $row[$i+2],
                                        'isCorrect'     =>  $isCorrect
                                    ]);
                                }
                            }
                            $this->updateNumQuestion($request->group_question_id);
                            DB::commit();

                        }catch (\Exception $ex){
                            DB::rollBack();
                            return response()->json([
                                'status'=>0,
                                'message'=>$ex->getMessage()
                            ]);
                        }
                    }

                }
            }else{
                return response()->json([
                    'status'    =>  500,
                    'messages'  =>  'D??? li???u tr???ng'
                ]);
            }
        }
        return response()->json([
            'status'=>200,
            'messages'  =>  'Th??m d??? li???u th??nh c??ng'
        ]);

    }
}

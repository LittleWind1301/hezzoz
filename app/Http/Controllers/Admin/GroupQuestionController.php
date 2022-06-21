<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SubjectV2;
use Illuminate\Http\Request;
use App\Subject;
use App\GroupQuestion;


class GroupQuestionController extends Controller
{
    private $groupQuestion;

    public function __construct(GroupQuestion $groupQuestion)
    {
        $this->groupQuestion = $groupQuestion;
    }

    public function groupQuestionOfSubject($subject_id){

        $subjectById = SubjectV2::find($subject_id);

        return view('admin.question_store.group_question.listGroupQuestion', [
            'subjectById'=>$subjectById
        ]);
    }

    public function store(Request $request){
        $data = [
            'name'=>$request->name,
            'subject_id'=>$request->subject_id,
            'numQuestion'=>0,
            'status'=>true
        ];
        $this->groupQuestion->create($data);

        return response()->json([
            'status'=>200
        ]);
    }

    public function fetchAll(Request $request){


        $groupQuestions = $this->groupQuestion->where('subject_id', $request->subject_id)->get();

        $output = '';
        if(count($groupQuestions)>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Nhóm</th>
                                    <th>Số Câu Hỏi</th>
                                    <th>Câu Hỏi</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
                foreach ($groupQuestions as $item){

                    $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$item->name.'</td>
                                    <td>'.$item->numQuestion.'</td>
                                    <td>
                                        <a href="'.route('questions.questionOfGroup', ['groupQuestionId'=>$item->id]).'" class="btn btn-success btn-sm">
                                            <i class="fa fa-folder"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editGrQuestModal">
                                            <i class="bi-pencil-square h4"></i>
                                        </a>

                                        <a href="javascript:;" onclick="deleteItem('.$item->id.')" class="text-danger mx-1 deleteIcon">
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

    public function edit(Request $request){
        $groupQuestById = $this->groupQuestion::find($request->id);
        return response()->json($groupQuestById);
    }


    public function update(Request $request){
        $groupQuestionById = $this->groupQuestion::find($request->groupQuestionId);

        $data = [
            'name'=>$request->name,
        ];

        $groupQuestionById->update($data);

        return response()->json([
            'status'=>200
        ]);
    }

    public function delete(Request $request){

        try{
            $this->groupQuestion::find($request->id)->delete();

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

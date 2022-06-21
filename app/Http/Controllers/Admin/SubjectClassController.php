<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Clazz;
use App\Subject;
use App\Subject_class;

class SubjectClassController extends Controller
{

    private $subject_class;

    public function __construct(Subject_class $subject_class)
    {
        $this->subject_class = $subject_class;
    }

    public function index()
    {
        $clazzes = Clazz::all();
        $subjects = Subject::all();
        return view('admin.subject_class.index', ['clazzes' => $clazzes, 'subjects' => $subjects]);
    }

    public function add(Request $request)
    {
        $data = [
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id
        ];
        $this->subject_class->create($data);

        return response()->json([
                'status' => 200
            ]
        );
    }

    public function fetchAll()
    {
        $subject_classes = $this->subject_class::all();
        $output = '';
        if($subject_classes->count()>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Lớp Học</th>
                                    <th>Môn Học</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
                foreach ($subject_classes as $item){
                    $className = Clazz::find($item->class_id)->class_name;
                    $subjectName = Subject::find($item->subject_id)->subject_name;
                    
                    $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$className.'</td>
                                    <td>'.$subjectName.'</td>
                                    <td>
                                        <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editModal">
                                        <i class="bi-pencil-square h4"></i></a>
                                    </td>
                                </tr>';
                }
                $output .='</tbody></table>';
                echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function edit(Request $request)
    {
        $subject_class = $this->subject_class::find($request->id);

        $classId = Clazz::find($subject_class->class_id)->id;
        $subjectId = Subject::find($subject_class->subject_id)->id;
        return response()->json([
            'id'=>$request->id,
            'class_id' => $classId,
            'subject_id' => $subjectId
        ]);
    }

    public function update(Request $request){
        $subject_class = $this->subject_class::find($request->subject_class_id);

        $subject_class->update([
            'class_id'=>$request->class,
            'subject_id'=>$request->subject
        ]);

        return response()->json([
            'status'=>200
        ]);
    }

    public function delete(Request $request){
        $this->subject_class::destroy($request->id);

        return response()->json([
            'status'=>200
        ]);
    }
}

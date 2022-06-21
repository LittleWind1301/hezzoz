<?php

namespace App\Http\Controllers\Admin;

use App\Clazz;
use App\Exam;
use App\Http\Controllers\Controller;
use App\Schedule;
use App\SubjectV2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\Binary;

class ScheduleController extends Controller
{
    private $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function schedulesOfClass($class_id){
        $classById = Clazz::find($class_id);

        return view('admin.class.schedule.schedulesOfClass',[
            'classById' =>  $classById
        ]);
    }
    public function fetchAll(Request $request){

        $classById = Clazz::find($request->class_id);

        $listSchedule = $classById->schedules;
        $output = '';
        if(count($listSchedule)>0){
            $output .= '<table id="myTable" class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu đề</th>
                                    <th>Thời gian bắt đầu</th>
                                    <th>Thời gian kết thúc</th>
                                    <th>Thời gian thi</th>
                                    <th>Thời gian thu bài</th>
                                    <th>Giám thị</th>
                                    <th>Đề thi</th>
                                    <th>Trạng thái</th>
                                    <th>Kết quả</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';

            $i = 0;
            foreach ($listSchedule as $item){
                $addExam = '';
                if(empty($item->exam_id)){
                    $addExam = '<a href="#" id="'.$item->id.'" class="btn btn-warning getListExam" data-toggle="modal" data-target="#addExamModal">
                                        Thêm bài thi
                                 </a>';
                }else{
                    $addExam =  '<a href="#" id="'.$item->id.'" class="btn btn-info viewExam" data-toggle="modal" data-target="#viewExamModal">
                                        Xem bài thi
                                 </a>';
                }

                $examStatus = '';
                $btnAction = '';
                $btnResult = '';
                if($item->exam_status == -1 && (Auth::user()->utype == 'EDU' || Auth::user()->utype == 'MASTER')){
                    $examStatus = '<button class="btn btn-primary">Chưa bắt đầu</button>';

                    $btnAction = '<a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editModal">
                                            <i class="bi-pencil-square h4"></i>
                                        </a>

                                        <a href="javascript:;" id="'.$item->id.'" onclick="deleteItem('.$item->id.')" class="text-danger mx-1 deleteIcon">
                                            <i class="bi-trash h4"></i>
                                        </a>';
                }elseif($item->exam_status == 0){
                    $examStatus = '<button class="btn btn-info">Đang thi</button>';

                }elseif($item->exam_status == 1){
                    $examStatus = '<button class="btn btn-success">Đã kết thúc</button>';
                    $btnResult = '<a href="'.route('examResults.index', ['schedule_id'=>$item->id]).'" class="btn btn-success btn-sm">
                                        <i class="fa fa-folder"></i>
                                  </a>';
                }

                $i ++;
                $output .='<tr>
                                    <td>'.$i.'</td>
                                    <td>'.$item->title.'</td>
                                    <td>'.$item->timeStart.'</td>
                                    <td>'.$item->timeFinish.'</td>
                                    <td>'.$item->limitTime.' phút</td>
                                    <td>'.$item->extraTime.' phút</td>
                                    <td>
                                        <a href="'.route('lecturersOfSchedule.index', ['schedule_id'=>$item->id]).'" class="btn btn-success btn-sm">
                                            <i class="fa fa-folder"></i>
                                        </a>
                                    </td>
                                    <td>'.$addExam.'</td>
                                    <td>'.$examStatus.'</td>
                                    <td>'.$btnResult.'</td>
                                    <td>'.$btnAction.'</td>
                                </tr>';
            }
            $output .='</tbody></table>';
            echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }

    }

    public function store(Request $request){
        try {
            DB::beginTransaction();
            $this->schedule->create([
                'title'         =>  $request->title,
                'timeStart'     =>  $request->timeStart,
                'timeFinish'    =>  $request->timeFinish,
                'limitTime'     =>  $request->limitTime,
                'extraTime'     =>  $request->extraTime,
                'class_id'      =>  $request->class_id
            ]);
            DB::commit();

            return response()->json([
                'status'    =>  200,
                'messages'  =>  'Tạo lịch thi thành công'
            ]);

        }catch (\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'    =>  500,
                'messages'  =>  $ex->getMessage()
            ]);
        }
    }

    public function edit(Request $request){
        $scheduleById = Schedule::find($request->id);
        return response()->json($scheduleById);
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
            $scheduleById = Schedule::find($request->schedule_id);
            $scheduleById->update([
                'title' =>  $request->title,
                'timeStart' =>  $request->timeStart,
                'timeFinish'    =>  $request->timeFinish,
                'limitTime'     =>  $request->limitTime,
                'extraTime'     =>  $request->extraTime
            ]);
            DB::commit();

            return response()->json([
                'status'    =>  200,
                'messages'  =>  'Cập nhật thành công'
            ]);

        }catch (\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'    =>  500,
                'messages'  =>  $ex->getMessage()
            ]);
        }
    }

    public function delete(Request $request){
        try{
            DB::beginTransaction();
            $this->schedule::find($request->id)->delete();
            DB::commit();
            return response()->json([
                'status'=>200,
                'message'=>'Xoá dữ liệu thành công'
            ]);
        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>500,
                'message'=>'Thất bại'
            ]);
        }
    }

    public function getListExam(Request $request){
        $scheduleById = $this->schedule->find($request->schedule_id);
        $classById =  Clazz::find($scheduleById->class_id);
        $subject_id = $classById->subject_id;
        $listExamInSubject = Exam::where('subjectId', $subject_id)->get();

        $output = '';

        if (count($listExamInSubject) > 0) {
            $output .= '<table id="addExamTable" class="table table-hover table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>ID</th>
                                    <th>Mã bài thi</th>
                                    <th>Tiêu đề</th>
                                    <th>Mô tả</th>
                                    <th>Số câu hỏi</th>
                                    <th>Học kì</th>
                                    <th>Điểm tối đa</th>
                                    <th>Số mã đề</th>
                                    <th>Người tạo</th>

                                </tr>
                            </thead>
                            <tbody>';
            foreach ($listExamInSubject as $item) {
                $output .= '<tr>
                                <td>
                                    <input type="hidden" name="schedule_id" value="'.$request->schedule_id.'">
                                    <input type="radio"
                                           class=""
                                           name="exam_id"
                                           value="' .$item->id. '">
                                </td>
                                <td>' . $item->id . '</td>
                                <td>' . $item->exam_id . '</td>
                                <td>' . $item->title . '</td>
                                <td>' . $item->description . '</td>
                                <td>' . $item->total_question . '</td>
                                <td>' . $item->semester . '</td>
                                <td>' . $item->maxPoint . '</td>
                                <td>' . $item->numberOfCodeExam . '</td>
                                <td>' . $item->creator . '</td>


                            </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function addExam(Request $request){
        try {
            DB::beginTransaction();
            $scheduleById = $this->schedule->find($request->schedule_id);
            $scheduleById->update([
                'exam_id'=>$request->exam_id
            ]);
            DB::commit();
            return response()->json([
                'status'=>200,
                'messages'=>'Đã thêm bài thi vào lịch thi'
            ]);
        }catch (\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>500,
                'messages'=>$ex->getMessage()
            ]);
        }

    }

    public function viewDetailExam(Request $request){
        $scheduleById = $this->schedule->find($request->schedule_id);
        $exam = Exam::find($scheduleById->exam_id);

        $exam_status = $scheduleById->exam_status;
        $btnDeleteExam = '';
        if ($exam_status==-1){
            $btnDeleteExam =
                '<a href="javascript:;" onclick="deleteExam('.$request->schedule_id.')" class="text-danger mx-1">
                    <i class="bi-trash h4"></i>
                </a>';
        }

        $output = '';
        $output .= '<table class="table table-hover table-striped text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã bài thi</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
                                <th>Số câu hỏi</th>
                                <th>Học kì</th>
                                <th>Điểm tối đa</th>
                                <th>Số mã đề</th>
                                <th>Người tạo</th>
                                 <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> <input type="hidden" name="schedule_id" value="'.$request->schedule_id.'">
                                ' . $exam->id . '
                                </td>
                                <td>' . $exam->exam_id . '</td>
                                <td>' . $exam->title . '</td>
                                <td>' . $exam->description . '</td>
                                <td>' . $exam->total_question . '</td>
                                <td>' . $exam->semester . '</td>
                                <td>' . $exam->maxPoint . '</td>
                                <td>' . $exam->numberOfCodeExam . '</td>
                                <td>' . $exam->creator . '</td>
                                <td>' .$btnDeleteExam. '</td>
                            </tr>
                        </tbody>
                    </table>';
        echo $output;
    }

    public function deleteExam(Request $request){
        try {
            $scheduleById = $this->schedule->find($request->id);
            $scheduleById->update([
                'exam_id'   =>  null
            ]);
            return response()->json([
                'status'    =>  200,
                'messages'  =>  'Đã xoá bài thi'
            ]);
        }catch (\Exception $ex){
            return response()->json([
                'status'    =>  500,
                'messages'  =>  $ex->getMessage()
            ]);
        }
    }
}

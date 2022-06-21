<?php

namespace App\Http\Controllers\Admin;

use App\Clazz;
use App\course;
use App\Exam_class;
use App\Exam_lecturers;
use App\Http\Controllers\Controller;
use App\LecturersOfSchedule;
use App\ProfileLecturers;
use App\Schedule;
use App\SubjectV2;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LecturersOfScheduleController extends Controller
{
    private $lecturers_schedule;

    public function __construct(LecturersOfSchedule $lecturers_schedule)
    {
        $this->lecturers_schedule = $lecturers_schedule;
    }

    public function lecturersOfSchedule($schedule_id)
    {

        $scheduleById = Schedule::find($schedule_id);
        return view('admin.class.schedule.lecturersOfSchedule.listLecturers', [
            'scheduleById' => $scheduleById
        ]);
    }

    public function fetchAll(Request $request)
    {

        $lecturersOfSchedule = $this->lecturers_schedule::all()->where('schedule_id', $request->schedule_id);

        $output = '';

        if (count($lecturersOfSchedule) > 0) {
            $output .= '<table id="examTable" class="table table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Mã GV</th>
                                    <th>Tên Giảng Viên</th>
                                    <th>Ngày Sinh</th>
                                    <th>Giới tính</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($lecturersOfSchedule as $item) {

                $lecturers = \App\User::find($item->user_id)->profileLecturers;
                $email = User::find($item->user_id)->email;
                $output .= '<tr>
                                <td>' . $email . '</td>
                                <td>' . $lecturers->lecturers_id . '</td>
                                <td>' . $lecturers->lecturers_name . '</td>
                                <td>' . $lecturers->dateOfBirth . '</td>
                                <td>' . $lecturers->gender . '</td>
                                <td>
                                    <a href="javascript:;" id="" onclick="deleteItem(' . $item->id . ')" class="text-danger mx-1 deleteIcon">
                                        <i class="bi-trash h4"></i>
                                    </a>
                                </td>
                            </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function create(Request $request)
    {

        $scheduleById = Schedule::find($request->schedule_id);
        $class = Clazz::find($scheduleById->class_id);
        $subject = SubjectV2::find($class->subject_id);
        $course = course::find($subject->course_id);

        $lecturersOfCourse = ProfileLecturers::all()->where('course_id', $course->id);

        $listLecturers = [];
        $lecturers_schedule = LecturersOfSchedule::all()->where('schedule_id', $request->schedule_id);

        foreach ($lecturersOfCourse as $item) {
            if (!$lecturers_schedule->contains('user_id', $item->user_id)) {
                $listLecturers[] = $item;
            }
        }

        $output = '';
        if (count($listLecturers) > 0) {
            $output .= '<table id="mytable" class="table table-hover table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>Email</th>
                                    <th>Mã GV</th>
                                    <th>Tên Giảng Viên</th>
                                    <th>Ngày Sinh</th>
                                    <th>Giới Tính</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($listLecturers as $item) {
                $email = User::find($item->user_id)->email;
                $output .= '<tr>
                                <td>
                                    <input type="checkbox"
                                           class="checkbox_exam"
                                           name="user_id[]"
                                           value="' . $item->user_id . '">
                                </td>
                                <td>' . $email . '</td>
                                <td>' . $item->lecturers_id . '</td>
                                <td>' . $item->lecturers_name . '</td>
                                <td>' . $item->dateOfBirth . '</td>
                                <td>' . $item->gender . '</td>
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
        try {
            DB::beginTransaction();
            foreach ($request->user_id as $user_id) {
                $this->lecturers_schedule->create([
                    'user_id' => $user_id,
                    'schedule_id' => $request->schedule_id
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'messages' => $ex->getMessage()
            ]);
        }

    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->lecturers_schedule->destroy($request->id);
            DB::commit();

            return response()->json([
                'status' => 200
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'messages'=>    $ex->getMessage()
            ]);
        }
    }

}

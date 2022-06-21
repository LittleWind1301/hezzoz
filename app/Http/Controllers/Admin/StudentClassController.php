<?php

namespace App\Http\Controllers\Admin;

use App\course;
use App\GroupQuestion;
use App\Http\Controllers\Controller;
use App\ProfileStudent;
use App\SubjectV2;
use App\User;
use Illuminate\Http\Request;
use App\Clazz;
use App\Student;
use App\Student_class;

class StudentClassController extends Controller
{
    private $student_class;

    public function __construct(Student_class $student_class)
    {
        $this->student_class = $student_class;
    }

    public function studentOfClass($class_id){
        $classById = Clazz::Find($class_id);
        $studentOfFaculty = ProfileStudent::all()->where('faculty_id', $classById->faculty_id);

        return view('admin.student_class.studentsOfClass', [
            'classById'=>$classById, 'studentOfFaculty'=>$studentOfFaculty]);
    }

    //hiển thị danh sách sinh viên cần thêm lên modal
    public function create(Request $request)
    {
        $classById = Clazz::Find($request->class_id);
        $subject = SubjectV2::find($classById->subject_id);
        $course = course::find($subject->course_id);
        $studentOfFaculty = ProfileStudent::all()->where('faculty_id', $course->faculty_id);

        $student_class = $this->student_class::all()->where('class_id', $request->class_id);
        $listStudent = [];
        foreach ($studentOfFaculty as $student){
            if(!$student_class->contains('student_id', $student->user_id) ){
                $listStudent[] = $student;
            }
        }

        $output = '';

        if(count($listStudent)>0){
            $output .= '<table id="mytable" class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>Email</th>
                                    <th>Mã SV</th>
                                    <th>Tên Sinh Viên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Ngày Sinh</th>
                                    <th>Khoá</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($listStudent as $item) {
                $output .= '<tr>
                                <td>
                                    <input type="checkbox"
                                           class="checkbox_student"
                                           name="student_id[]"
                                           value="'.$item->user->id.'">
                                </td>
                                <td>'.$item->user->email.'</td>
                                <td>'.$item->student_id.'</td>
                                <td>'.$item->student_name.'</td>
                                <td>'.$item->phoneNumber.'</td>
                                <td>'.$item->dateOfBirth.'</td>
                                <td>K'.$item->yearOfAdmission.'</td>
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

        foreach ($request->student_id as $student_id){
            $this->student_class->create([
                'student_id'=>  $student_id,
                'class_id'  =>  $request->class_id
            ]);
        }

        $this->updateTotalStudentInClass($request->class_id);
        return response()->json([
                'status' => 200
            ]);
    }

    public function fetchAll(Request $request)
    {
        $student_class = $this->student_class::all()->where('class_id', $request->class_id);
        $output = '';

        if (count($student_class) > 0) {
            $output .= '<table id="tableAllStudent" class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Mã SV</th>
                                    <th>Tên Sinh Viên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Ngày Sinh</th>
                                    <th>Giới Tính</th>
                                    <th>Khoá</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($student_class as $item) {
                $student = User::find($item->student_id)->profileStudent;
                $email = User::find($item->student_id)->email;
                $output .= '<tr>
                                <td>'.$email.'</td>
                                <td>'.$student->student_id.'</td>
                                <td>'.$student->student_name.'</td>
                                <td>'.$student->phoneNumber.'</td>
                                <td>'.$student->dateOfBirth.'</td>
                                <td>'.$student->gender.'</td>
                                <td>K'.$student->yearOfAdmission.'</td>
                                <td>
                                    <a href="javascript:;" id="" onclick="deleteItem('.$item->id.')" class="text-danger mx-1 deleteIcon">
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



    public function delete(Request $request){

        $student_class = Student_class::find($request->id);
        $class_id = $student_class->class_id;

        $student_class::destroy($request->id);

        $this->updateTotalStudentInClass($class_id);

        return response()->json([
            'status'=>200
        ]);
    }

    public function updateTotalStudentInClass($class_id){
        $class = Clazz::find($class_id);

        $student_class = Student_class::all()->where('class_id', $class_id);

        $class->update([
            'total_student'=>count($student_class)
        ]);
    }
}

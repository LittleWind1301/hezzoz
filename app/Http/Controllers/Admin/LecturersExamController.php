<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Clazz;
use App\Exam;
use App\Exam_class;
use App\Exam_lecturers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;

class LecturersExamController extends Controller
{
    private $attendance;
    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }





}

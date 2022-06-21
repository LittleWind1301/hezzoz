<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' ], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::get('login', 'Admin\AdminController@loginAdmin')->name('login-form');
Route::post('login', 'Admin\AdminController@postLoginAdmin')->name('login');

Route::post('logout', 'Admin\AdminController@logout')->name('logout');

// Route::get('admin/home', function () {
//     return view('admin.home');
// });

Route::get('admin/', 'Admin\HomeController@index')
    ->name('admin')
    ->middleware('auth');

Route::get('trang-chu/', 'Student\HomeController@index')
    ->name('home')
    ->middleware('auth');



Route::prefix('admin')->group(function () {

    Route::prefix('khoa-cua-truong')->group(function () {
        Route::get('/', [
            'as' => 'faculties.index',
            'uses' => 'Admin\FacultyController@index'
        ])->middleware('auth');

        Route::post('/store', [
            'as' => 'faculties.store',
            'uses' => 'Admin\FacultyController@store'
        ]);

        Route::get('/fetch-all', [
            'as' =>'faculties.fetchAll',
            'uses'=>'Admin\FacultyController@fetchAll'
        ]);

        Route::get('/edit', [
            'as' => 'faculties.edit',
            'uses'=>'Admin\FacultyController@edit'
        ]);

        Route::post('/update', [
            'as' => 'faculties.update',
            'uses'=>'Admin\FacultyController@update'
        ]);

        Route::post('/delete', [
            'as' => 'faculties.delete',
            'uses' => 'Admin\FacultyController@delete'
        ]);

        Route::post('/change-status', [
            'as' => 'faculties.changeStatus',
            'uses' => 'Admin\FacultyController@changeStatus'
        ]);

        Route::prefix('/bo-mon')->group(function () {

            Route::get('/ma-khoa={faculty_id}', [
                'as' => 'courses.index',
                'uses' => 'Admin\CourseController@index'
            ]);

            Route::get('/fetch-all', [
                'as' =>'courses.fetchAll',
                'uses'=>'Admin\CourseController@fetchAll'
            ]);

            Route::post('/store', [
                'as' => 'courses.store',
                'uses' => 'Admin\CourseController@store'
            ]);

            Route::get('/edit', [
                'as' => 'courses.edit',
                'uses'=>'Admin\CourseController@edit'
            ]);

            Route::post('/update', [
                'as' => 'courses.update',
                'uses'=>'Admin\CourseController@update'
            ]);

            Route::post('/change-status', [
                'as' => 'courses.changeStatus',
                'uses' => 'Admin\CourseController@changeStatus'
            ]);

            Route::post('/delete', [
                'as' => 'courses.delete',
                'uses' => 'Admin\CourseController@delete'
            ]);

            Route::prefix('/mon-hoc')->group(function () {

                Route::get('/ma-bo-mon={course_id}', [
                    'as' => 'subjects.subjectOfCourse',
                    'uses' => 'Admin\SubjectController@subjectOfCourse',
                ]);

                Route::get('/fetch-subject-of-course', [
                    'as' => 'subjects.fetchSubjectOfCourse',
                    'uses' => 'Admin\SubjectController@fetchSubject',
                ]);

                Route::post('/store', [
                    'as' => 'subjects.store',
                    'uses' => 'Admin\SubjectController@store'
                ]);

                Route::get('/edit', [
                    'as' => 'subjects.edit',
                    'uses'=>'Admin\SubjectController@edit'
                ]);

                Route::post('/update', [
                    'as' => 'subjects.update',
                    'uses'=>'Admin\SubjectController@update'
                ]);

                Route::post('/delete', [
                    'as' => 'subjects.delete',
                    'uses' => 'Admin\SubjectController@delete'
                ]);
            });
        });
    });



    //route quản lý lớp học
    Route::prefix('lop-hoc-phan')->group(function () {

        Route::get('/', [
            'as' => 'classes.listFaculty',
            'uses' => 'Admin\ClassController@listFaculty'
        ]);

        Route::get('/subject_id={subject_id}', [
            'as'=>'classes.classesOfSubject',
            'uses'=>'Admin\ClassController@classesOfSubject'
        ]);


        Route::get('/fetch-all', [
            'as' =>'classes.fetchAll',
            'uses'=>'Admin\ClassController@fetchAll'
        ]);

        Route::post('/store', [
            'as' => 'classes.store',
            'uses' => 'Admin\ClassController@store'
        ]);

        Route::get('/edit', [
            'as' => 'classes.edit',
            'uses'=>'Admin\ClassController@edit'
        ]);

        Route::post('/update', [
            'as' => 'classes.update',
            'uses'=>'Admin\ClassController@update'
        ]);

        Route::post('/delete', [
            'as' => 'classes.delete',
            'uses' => 'Admin\ClassController@delete'
        ]);


        //route quản lý sinh viên trong từng lớp
        Route::prefix('student-in-class')->group(function () {

            Route::get('/list-student-of-class/class={class_id}', [
                'as' => 'studentOfClasses.studentOfClass',
                'uses' => 'Admin\StudentClassController@studentOfClass',
            ]);

            Route::get('/fetch-all', [
                'as'=>'studentOfClasses.fetchAll',
                'uses'=>'Admin\StudentClassController@fetchAll'
            ]);

            Route::post('/store', [
                'as'=>'student_classes.store',
                'uses'=>'Admin\StudentClassController@store'
            ]);

            Route::get('/create', [
                'as'=>'student_classes.create',
                'uses'=>'Admin\StudentClassController@create'
            ]);

            Route::get('/edit', [
                'as'=>'student_classes.edit',
                'uses'=>'Admin\StudentClassController@edit'
            ]);

            Route::post('/update', [
                'as'=>'student_classes.update',
                'uses'=>'Admin\StudentClassController@update'
            ]);

            Route::post('/delete', [
                'as'=>'student_classes.delete',
                'uses'=>'Admin\StudentClassController@delete'
            ]);
        });

        //route quản lý lich thi trong từng lớp học phần
        Route::prefix('lich-thi')->group(function () {
            Route::get('/classId={class_id}', [
                'as' => 'schedules.schedulesOfClass',
                'uses' => 'Admin\ScheduleController@schedulesOfClass'
            ]);

            Route::get('/fetch-all', [
                'as'=>'schedules.fetchAll',
                'uses'=>'Admin\ScheduleController@fetchAll'
            ]);

            Route::post('/store', [
                'as'=>'schedules.store',
                'uses'=>'Admin\ScheduleController@store'
            ]);

            Route::get('/edit', [
                'as'=>'schedules.edit',
                'uses'=>'Admin\ScheduleController@edit'
            ]);

            Route::post('/update', [
                'as' => 'schedules.update',
                'uses'=>'Admin\ScheduleController@update'
            ]);


            Route::post('/delete', [
                'as' => 'schedules.delete',
                'uses' => 'Admin\ScheduleController@delete'
            ]);

            Route::get('/list-exam', [
                'as'=>'schedules.getListExam',
                'uses'=>'Admin\ScheduleController@getListExam'
            ]);


            Route::post('/add-exam', [
                'as'=>'schedules.addExam',
                'uses'=>'Admin\ScheduleController@addExam'
            ]);

            Route::get('/view-exam-info', [
                'as'=>'schedules.viewDetailExam',
                'uses'=>'Admin\ScheduleController@viewDetailExam'
            ]);

            Route::post('/delete-exam', [
                'as'=>'schedules.deleteExam',
                'uses'=>'Admin\ScheduleController@deleteExam'
            ]);
        });

        //route quản lý giám thị theo lịch thi
        Route::prefix('giam-thi')->group(function () {
            Route::get('/lich-thi={schedule_id}', [
                'as' => 'lecturersOfSchedule.index',
                'uses' => 'Admin\LecturersOfScheduleController@lecturersOfSchedule'
            ]);

            Route::get('/fetch-all', [
                'as'=>'lecturersOfSchedule.fetchAll',
                'uses'=>'Admin\LecturersOfScheduleController@fetchAll'
            ]);

            Route::get('/create', [
                'as'=>'lecturersOfSchedule.create',
                'uses'=>'Admin\LecturersOfScheduleController@create'
            ]);

            Route::post('/store', [
                'as'=>'lecturersOfSchedule.store',
                'uses'=>'Admin\LecturersOfScheduleController@store'
            ]);

            Route::post('/delete', [
                'as'=>'lecturersOfSchedule.delete',
                'uses'=>'Admin\LecturersOfScheduleController@delete'
            ]);
        });

        //route xem kết quả bài thi theo lịch thi
        Route::prefix('ket-qua-thi')->group(function () {
            Route::get('/lich-thi={schedule_id}', [
                'as' => 'examResults.index',
                'uses' => 'Admin\ExamResultController@index'
            ]);

            Route::get('/fetch-all', [
                'as'=>'examResults.fetchAll',
                'uses'=>'Admin\ExamResultController@fetchAll'
            ]);

            Route::get('/chi-tiet/lich-thi={schedule_id}&sinh-vien={user_id}', [
                'as' => 'resultExams.detailExamResult',
                'uses' => 'Admin\ExamResultController@detailExamResult'
            ]);

            Route::get('/export-pdf/lich-thi={schedule_id}', [
                'as' => 'resultExams.generatePDF',
                'uses' => 'Admin\ExamResultController@generatePDF'
            ]);
        });

        //route quản lý đề thi trong từng lớp học   //bo
        Route::prefix('exam-in-class')->group(function () {
            Route::get('/exam-of-class/class={class_id}', [
                'as' => 'examOfClass.examOfClass',
                'uses' => 'Admin\ExamClassController@examOfClass'
            ]);

            Route::get('/fetch-all', [
                'as'=>'examOfClass.fetchAll',
                'uses'=>'Admin\ExamClassController@fetchAll'
            ]);

            Route::get('/create', [
                'as'=>'examOfClass.create',
                'uses'=>'Admin\ExamClassController@create'
            ]);

            Route::post('/store', [
                'as'=>'examOfClass.store',
                'uses'=>'Admin\ExamClassController@store'
            ]);

            Route::post('/delete', [
                'as'=>'examOfClass.delete',
                'uses'=>'Admin\ExamClassController@delete'
            ]);

            //route quản lý kết quả từng bài thi trong lớp //bo
            Route::prefix('result-exam-in-class')->group(function () {

                Route::get('/view-result/examClass={exam_class_id}', [
                    'as' => 'resultExams.viewResult',
                    'uses' => 'Admin\ExamResultController@viewResult'
                ]);

                Route::get('/export-pdf/{exam_class_id}', [
                    'as' => 'resultExams.exportPDF',
                    'uses' => 'Admin\ExamResultController@exportPDF'
                ]);

                Route::get('/view-student-answer/examClass={exam_class_id}&userId={user_id}', [
                    'as' => 'resultExams.viewStudentAnswer',
                    'uses' => 'Admin\ExamResultController@viewStudentAnswer'
                ]);

                Route::post('/publish-result', [
                    'as'=>'resultExams.publishResult',
                    'uses'=>'Admin\ExamClassController@publishResult'
                ]);

            });
        });
    });


    //module quản lý giảng viên
    Route::prefix('lecturers')->group(function () {
        Route::get('/', [
            'as' => 'lecturers.listFaculty',
            'uses' => 'Admin\LecturersController@listFaculty'
        ]);

        Route::get('/danh-sach-giang-vien/bo-mon={course_id}', [
            'as' => 'lecturers.lecturersOfCourse',
            'uses' => 'Admin\LecturersController@lecturersOfCourse',
        ]);

        Route::get('/fetch-all', [
            'as'=>'lecturers.fetchAll',
            'uses'=>'Admin\LecturersController@fetchAll'
        ]);

        Route::post('/store', [
            'as'=>'lecturers.store',
            'uses'=>'Admin\LecturersController@store'
        ]);

        Route::post('/import',[
            'as'=>'lecturers.storeImport',
            'uses'=>'Admin\LecturersController@storeImport'
        ]);

        Route::get('/edit', [
            'as'=>'lecturers.edit',
            'uses'=>'Admin\LecturersController@edit'
        ]);

        Route::post('/update', [
            'as'=>'lecturers.update',
            'uses'=>'Admin\LecturersController@update'
        ]);

        Route::post('/delete', [
            'as'=>'lecturers.delete',
            'uses'=>'Admin\LecturersController@delete'
        ]);

        Route::get('/thong-tin-ca-nhan', [
            'as' => 'lecturers.profile',
            'uses' => 'Admin\LecturersController@profile'
        ]);
    });

    //module quản lý sinh viên
    Route::prefix('student')->group(function () {

        Route::get('/', [
            'as' => 'students.listFaculty',
            'uses' => 'Admin\StudentController@listFaculty'
        ])->middleware('auth');

        Route::get('/list-student-of-faculty/{faculty_id}', [
            'as' => 'students.studentOfFaculty',
            'uses' => 'Admin\StudentController@studentOfFaculty',
        ]);


        Route::get('/fetch-all', [
            'as'=>'students.fetchAll',
            'uses'=>'Admin\StudentController@fetchAll'
        ]);

        Route::post('/import',[
            'as'=>'students.storeImport',
            'uses'=>'Admin\StudentController@storeImport'
        ]);

        Route::post('/store', [
            'as'=>'students.store',
            'uses'=>'Admin\StudentController@store'
        ]);

        Route::get('/edit', [
            'as'=>'students.edit',
            'uses'=>'Admin\StudentController@edit'
        ]);

        Route::post('/update', [
            'as'=>'students.update',
            'uses'=>'Admin\StudentController@update'
        ]);

        Route::post('/delete', [
            'as'=>'students.delete',
            'uses'=>'Admin\StudentController@delete'
        ]);
    });



    //module quản lý kho đề thi
    Route::prefix('kho-de-thi')->group(function (){

        Route::get('/', [
            'as' => 'examManagements.listFaculty',
            'uses' => 'Admin\ExamController@listFaculty'
        ]);

        //quan ly đề thi trong từng học phần
        Route::prefix('/de-thi')->group(function () {

            Route::get('/subject_id={subject_id}', [
                'as'=>'exams.examsOfSubject',
                'uses'=>'Admin\ExamController@examsOfSubject'
            ]);

            Route::get('/create/subject_id={subject_id}', [
                'as'=>'exams.create',
                'uses'=>'Admin\ExamController@create'
            ]);

            Route::get('/random-question', [
                'as'=>'exams.randomQuestion',
                'uses'=>'Admin\ExamController@randomQuestion'
            ]);

            Route::post('/store', [
                'as' => 'exams.store',
                'uses' => 'Admin\ExamController@store'
            ]);

            Route::get('/edit/{id}', [
                'as' => 'exams.edit',
                'uses' => 'Admin\ExamController@edit'
            ]);

            Route::post('/update/{id}', [
                'as' => 'exams.update',
                'uses' => 'Admin\ExamController@update'
            ]);

            Route::post('/delete', [
                'as' =>'exams.delete',
                'uses'=>'Admin\ExamController@delete'
            ]);
            //quan ly đề thi trong từng môn
            Route::prefix('/chi-tiet')->group(function () {
                Route::get('/bai-thi={exam_id}', [
                    'as'=>'exams.viewCodeExam',
                    'uses'=>'Admin\ExamController@viewCodeExam'
                ]);

                Route::get('/chi-tiet', [
                    'as'=>'exams.getDetailCodeExam',
                    'uses'=>'Admin\ExamController@getDetailCodeExam'
                ]);
            });

        });
    });

    //update route question
    Route::prefix('question-store')->group(function () {

        Route::get('/', [
            'as' => 'questionStores.listFaculty',
            'uses' => 'Admin\QuestionController@listFaculty'
        ]);

        Route::get('/course-of-faculty/faculty-id={faculty_id}', [
            'as' => 'questionStores.courseOfFaculty',
            'uses' => 'Admin\QuestionController@courseOfFaculty'
        ]);


        //route quan ly nhom cau hoi
        Route::prefix('/groupQuestions')->group(function () {

            Route::get('/subject_id={subject_id}', [
                'as'=>'groupQuestions.groupQuestionOfSubject',
                'uses'=>'Admin\GroupQuestionController@groupQuestionOfSubject'
            ]);

            Route::get('/fetch-all', [
                'as' =>'groupQuestions.fetchAll',
                'uses'=>'Admin\GroupQuestionController@fetchAll'
            ]);


            Route::post('/store', [
                'as' => 'groupQuestions.store',
                'uses' => 'Admin\GroupQuestionController@store'
            ]);

            Route::get('/edit', [
                'as' => 'groupQuestions.edit',
                'uses'=>'Admin\GroupQuestionController@edit'
            ]);

            Route::post('/update', [
                'as' => 'groupQuestions.update',
                'uses'=>'Admin\GroupQuestionController@update'
            ]);

            Route::post('/delete', [
                'as' => 'groupQuestions.delete',
                'uses' => 'Admin\GroupQuestionController@delete'
            ]);
            //quan li cau hoi
            Route::prefix('/question')->group(function () {

                Route::get('/groupQuestionId={groupQuestionId}', [
                    'as'=>'questions.questionOfGroup',
                    'uses'=>'Admin\QuestionController@questionOfGroup',
                ]);

                Route::get('/fetch-all', [
                    'as' =>'questions.fetchAll',
                    'uses'=>'Admin\QuestionController@fetchAll'
                ]);

                Route::get('/create/groupQuestionId={groupQuestionId}', [
                    'as'=>'questions.create',
                    'uses'=>'Admin\QuestionController@create',
                ]);

                Route::post('/store', [
                    'as'=>'questions.store',
                    'uses'=>'Admin\QuestionController@store'
                ]);

                Route::get('/edit/{id}',[
                    'as'=>'questions.edit',
                    'uses'=>'Admin\QuestionController@edit'
                ]);

                Route::post('/update/{id}',[
                    'as'=>'questions.update',
                    'uses'=>'Admin\QuestionController@update'
                ]);

                Route::post('/delete', [
                    'as' =>'questions.delete',
                    'uses'=>'Admin\QuestionController@delete'
                ]);


                Route::post('/import',[
                    'as'=>'questions.storeImport',
                    'uses'=>'Admin\QuestionController@storeImport'
                ]);
            });
        });

    });

    //module quản lý account
    Route::prefix('account')->group(function () {

        Route::get('/', [
            'as' => 'accounts.listFaculty',
            'uses' => 'Admin\AccountController@listFaculty'
        ]);

        Route::get('/course-of-faculty/faculty-id={faculty_id}', [
            'as' => 'accounts.courseOfFaculty',
            'uses' => 'Admin\AccountController@courseOfFaculty'
        ]);

        //Quản lý tài khoản quản trị khoa
        Route::get('/account-of-faculty/faculty-id={faculty_id}', [
            'as' => 'accounts.accountOfFaculty',
            'uses' => 'Admin\AccountController@accountOfFaculty'
        ]);

        Route::post('/store-account-faculty', [
            'as' =>'accounts.storeAccountFaculty',
            'uses'=>'Admin\AccountController@storeAccountFaculty'
        ]);

        Route::get('/fetch-account-faculty', [
            'as' =>'accounts.fetchAccountFaculty',
            'uses'=>'Admin\AccountController@fetchAccountFaculty'
        ]);

        Route::post('/delete-account-faculty', [
            'as' =>'accounts.deleteAccountFaculty',
            'uses'=>'Admin\AccountController@deleteAccountFaculty'
        ]);


        //Quản lý tài khoản quản trị bộ môn
        Route::get('/account-of-course/course-id={course_id}', [
            'as' => 'accounts.accountOfCourse',
            'uses' => 'Admin\AccountController@accountOfCourse'
        ]);

        Route::post('/store-account-course', [
            'as' =>'accounts.storeAccountCourse',
            'uses'=>'Admin\AccountController@storeAccountCourse'
        ]);

        Route::get('/fetch-account-course', [
            'as' =>'accounts.fetchAccountCourse',
            'uses'=>'Admin\AccountController@fetchAccountCourse'
        ]);

        Route::post('/delete-account-course', [
            'as' =>'accounts.deleteAccountCourse',
            'uses'=>'Admin\AccountController@deleteAccountCourse'
        ]);
    });

    //module đổi mật khẩu
    Route::prefix('change-password')->group(function () {

        Route::get('/', [
            'as' => 'password.changePassword',
            'uses' => 'Admin\AdminController@changePassword'
        ]);

        Route::post('/', [
            'as' => 'password.changePassword',
            'uses' => 'Admin\AdminController@postNewPassword'
        ]);
    });
});

//module giảng viên coi thi
Route::prefix('lich-coi-thi')->group(function () {

    Route::get('/', [
        'as' => 'supervisor.index',
        'uses' => 'Admin\SupervisorController@index'
    ]);

    Route::get('/fetch-all', [
        'as' =>'supervisor.fetchAll',
        'uses'=>'Admin\SupervisorController@fetchAll'
    ]);

    Route::get('/attendances-list', [
        'as' => 'supervisor.getAttendancesList',
        'uses' => 'Admin\SupervisorController@getAttendancesList'
    ]);

    Route::post('/start-exam', [
        'as' => 'supervisor.startExam',
        'uses' => 'Admin\SupervisorController@startExam'
    ]);

    Route::post('/finish-exam', [
        'as' => 'supervisor.finishExam',
        'uses' => 'Admin\SupervisorController@finishExam'
    ]);

    Route::get('/bai-thi={exam_id}', [
        'as'=>'supervisor.viewCodeExam',
        'uses'=>'Admin\SupervisorController@viewCodeExam'
    ]);

    Route::get('/chi-tiet', [
        'as'=>'supervisor.getDetailCodeExam',
        'uses'=>'Admin\SupervisorController@getDetailCodeExam'
    ]);

    Route::get('/export-result-pdf/lich-thi={schedule_id}', [
        'as' => 'supervisor.generatePDF',
        'uses' => 'Admin\SupervisorController@generatePDF'
    ]);

    Route::get('/export-code-exam-pdf/ma-de={codeExamId}', [
        'as' => 'supervisor.generateCodeExamPDF',
        'uses' => 'Admin\SupervisorController@generateCodeExamPDF'
    ]);
});


Route::prefix('student')->group(function () {

    Route::get('/fetch-all', [
        'as' =>'homeStudents.fetchAll',
        'uses'=>'Student\HomeController@fetchAll'
    ]);

    Route::post('/checkin', [
        'as' => 'homeStudents.checkin',
        'uses' => 'Student\HomeController@checkin'
    ]);

    Route::get('/exam-info', [
        'as' => 'homeStudents.examInfo',
        'uses' => 'Student\HomeController@examInfo'
    ]);

    Route::get('/detail-exam/{id}', [
        'as' => 'homeStudents.detailExam',
        'uses' => 'Student\HomeController@detailExam'
    ]);

    Route::get('/fetch-detail-exam', [
        'as' =>'homeStudents.fetchDetailExam',
        'uses'=>'Student\HomeController@fetchDetailExam'
    ]);

    Route::post('/post-answer', [
        'as' => 'homeStudents.postAnswer',
        'uses' => 'Student\HomeController@postAnswer'
    ]);

    Route::get('/ket-qua/{schedule_id}', [
        'as' => 'homeStudents.viewResult',
        'uses' => 'Student\HomeController@viewResult'
    ]);

    Route::prefix('thong-tin-ca-nhan')->group(function () {
        Route::get('/profileStudents', [
            'as' => 'profiles.index',
            'uses' => 'Student\ProfileController@index'
        ]);
        Route::prefix('change-password')->group(function () {

            Route::get('/', [
                'as' => 'profiles.changePassword',
                'uses' => 'Student\ProfileController@changePassword'
            ]);

            Route::post('/', [
                'as' => 'profiles.changePassword',
                'uses' => 'Student\ProfileController@postNewPassword'
            ]);
        });

    });
});



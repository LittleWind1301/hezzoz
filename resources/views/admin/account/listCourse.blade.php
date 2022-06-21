<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Quản lý tài khoản quản trị bộ môn</div>
                            </div>
                        </h5>
                        <div class="card-body">
                            @foreach($listCourse as $item)
                            <div class="shadow-lg p-3 mb-5 bg-white rounded">
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="btn-group dropright">
                                            <a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false">
                                                Bộ môn: {{$item->course_code}} -- {{$item->course_name}}
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                @foreach($item->subjects as $subject)
                                                <a class="dropdown-item" href="{{route('exams.examsOfSubject', ['subject_id'=>$subject->id])}}">
                                                    {{$subject->subject_name}}
                                                </a>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Tài Khoản Quản trị</h5>
                                        <a href="{{route('accounts.accountOfCourse', ['course_id'=>$item->id])}}" class="btn btn-success btn-sm">
                                            <i class="fa fa-folder"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

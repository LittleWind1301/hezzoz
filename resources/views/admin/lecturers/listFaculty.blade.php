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
                    <div class="col-6 shadow-lg p-3 mb-5 bg-white rounded">
                        <div class="card">
                            <div class="card-header">
                                <h2 >Quản lý giảng viên</h2>
                            </div>
                            <!-- ./card-header -->
                            <div class="card-body p-0">
                                <table class="table table-hover">
                                    <tbody>
                                    @foreach($listFaculty as $item)
                                        <tr data-widget="expandable-table" aria-expanded="true" style="background-color: rgb(23, 162, 184); color: white">
                                            <td class="rounded">
                                                <h5>
                                                    <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                                    Khoa : {{$item->faculty_code}} - {{$item->faculty_name}}
                                                </h5>

                                            </td>
                                        </tr>
                                        <tr class="expandable-body">
                                            <td>
                                                <div class="p-0">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                        @foreach($item->courses as $course)
                                                                <tr  style="background-color: rgb(108, 117, 125); color: white">
                                                                    <td class="rounded">
                                                                        <a  href="{{route('lecturers.lecturersOfCourse', ['course_id'=>$course->id])}}">
                                                                            <h4 style="color: white"> Bộ môn : {{$course->course_code}} - {{$course->course_name}}</h4>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

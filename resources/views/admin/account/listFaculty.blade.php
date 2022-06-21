<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">

                <br>
                <div class="row">
                    <div class="col-6 shadow-lg p-3 mb-5 bg-white rounded">
                        <div class="card">
                            <div class="card-header" style="background-color: #cccccc;">
                                <h4 >Tài khoản quản trị</h4>
                            </div>
                            <!-- ./card-header -->
                            <div class="card-body p-0">
                                <br>
                                <table class="table table-hover">
                                    <tbody>
                                    @foreach($listFaculty as $item)
                                    <tr data-widget="expandable-table" aria-expanded="true" style="background-color: rgb(23, 162, 184); color: white">
                                        <td class="row" >
                                            <div class="col-md-6">
                                                <h5>
                                                <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                                Khoa : {{$item->faculty_code}} - {{$item->faculty_name}}
                                                </h5>
                                            </div>
                                           <div class="col-md-6">
                                               <a href="{{route('accounts.accountOfFaculty', ['faculty_id'=>$item->id])}}" class="btn btn-default btn-sm">
                                                   <i class="fa fa-key"></i>
                                               </a>
                                           </div>

                                        </td>
                                    </tr>
                                    <tr class="expandable-body">
                                        <td>
                                            <div class="p-0">
                                                <table class="table table-hover">
                                                    <tbody>
                                                    @foreach($item->courses as $course)
                                                        <tr>
                                                            <td class="row">
                                                                <div class="col-md-5">
                                                                    Bộ môn : {{$course->course_code}} - {{$course->course_name}}
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <a href="{{route('accounts.accountOfCourse', ['course_id'=>$course->id])}}" class="btn btn-info btn-sm">
                                                                        <i class="fa fa-key"></i>
                                                                    </a>
                                                                </div>
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

<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Sinh Vien</title>
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">

                <br>
                <div class="row">
                    <div class="col-6 shadow-lg p-3 mb-5 bg-white rounded">
                        <div class="card">
                            <div class="card-header">
                                <h3>Quản lý sinh viên theo khoa</h3>
                            </div>
                            <!-- ./card-header -->
                            <div class="card-body p-0">
                                <table class="table">
                                    <tbody>
                                    @foreach($listFaculty as $item)
                                        <tr style="background-color: rgb(23, 162, 184); color: white">
                                            <td class="rounded">
                                                <a  href="{{route('students.studentOfFaculty', ['faculty_id'=>$item->id])}}">
                                                    <h4 style="color: white"> Khoa : {{$item->faculty_code}} - {{$item->faculty_name}}</h4>
                                                </a>

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

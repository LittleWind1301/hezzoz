<!-- Stored in resources/views/child.blade.php -->

@extends('student.layouts.student')

@section('title')
    <title>Trang cá nhân</title>
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <div class="content">
        <div class="container-fluid">
            <br>
            <div class="row justify-content-md-center">
                <div class="card col-md-8">
                    <div class="card-header" style="background-color: #172d56; color: white">
                        <h4> THÔNG TIN CÁ NHÂN </h4>
                    </div>

                    <div class="card-body">
                        <form action="">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="">Họ và tên</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->student_name}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Mã sinh viên</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->student_id}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Khoá</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->yearOfAdmission}}">
                                </div>
                            </div>

                            <br>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="">Số CMND/Thẻ căn cước</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->cmnd}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Ngày sinh</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->dateOfBirth}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Giới tính</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->gender}}">
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="">Số điện thoại sinh viên</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->phoneNumber}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Email</label>
                                    <input disabled type="text" class="form-control" value="{{$user->email}}">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Quê quán</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->province}}">
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="">Địa chỉ</label>
                                    <input disabled type="text" class="form-control" value="{{$userProfile->address}}">
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>

        </div>
    </div>

</div>


@endsection

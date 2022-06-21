<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

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
                    <div class="card col-md-4">
                        <div class="card-header" style="background-color: #1a88ff">
                            <h4 style="color: white"> ĐỔI MẬT KHẨU </h4>
                        </div>

                        <div class="card-body">
                            <form action="#" method="post" id="change_pwd_form"  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    Cần có mật khẩu mạnh. Nhập 3-256 ký tự. Không bao gồm các từ hoặc tên thường gặp. Kết hợp chữ hoa, chữ thường, số và ký hiệu.
                                </div>

                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Email đăng nhập:</label>
                                    <h5>{{\Illuminate\Support\Facades\Auth::user()->email}}</h5>
                                </div>

                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Mật khẩu cũ</label>
                                    <input type="password" class="form-control"  name="old_pwd">
                                    <span style="color: red" class="error old_pwd_error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Tạo mật khẩu mới:</label>
                                    <input type="password" class="form-control"  name="new_pwd">
                                    <span style="color: red" class="error new_pwd_error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Tạo mật khẩu mới:</label>
                                    <input type="password" class="form-control"  name="confirm_pwd">
                                    <span style="color: red" class="error confirm_pwd_error"></span>
                                </div>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

@section('js-index')
    <script>
        $("#change_pwd_form").submit(function (e){
            e.preventDefault();
            const fd= new FormData(this);
            $("#submit_btn").text('Đang cập nhật...');
            $('.error').text('')
            $.ajax({
                url: '{{ route('password.changePassword') }}',
                method: 'post',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                success: function (res){
                    if(res.status == 200){
                        Swal.fire(
                            'Đã Cập Nhật',
                            'Thay đổi mật khẩu thành công',
                            'success')
                        $("#submit_btn").text('Cập nhật');
                        $("#change_pwd_form")[0].reset();
                        $("#close_modal").click();

                    }else if(res.status === 500){
                        $('.old_pwd_error').text(res.messages)
                    }
                },
                error:function (error){
                    let responseJson = error.responseJSON.errors

                    if(Object.keys(responseJson).length > 0){
                        for(let key in responseJson){
                            $('.' + key + '_error').text(responseJson[key])
                        }
                    }
                }
            })
        })
    </script>


@endsection

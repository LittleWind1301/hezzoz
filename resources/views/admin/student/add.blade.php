<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Học Viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="text-align: left">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4"><h5>Email</h5></label>
                            <input name="email" type="email" class="form-control" placeholder="nhập email" value="{{old('email')}}">
                            <span class="error email_error" style="color: red"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4"><h5>Password</h5></label>
                            <input name="password" type="password" class="form-control" placeholder="nhập mật khẩu">
                            <span class="error password_error" style="color: red"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Mã Sinh Viên</h5></label>
                            <input name="student_id" type="text" class="form-control"  placeholder="nhập mã sinh viên">
                            <span class="error student_id_error" style="color: red"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Họ Và Tên</h5></label>
                            <input name="student_name" type="text" class="form-control"  placeholder="nhập họ và tên">
                            <span class="error student_name_error" style="color: red"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 ">
                            <label for=""><h5>Số Điện Thoại</h5></label>
                            <input name="phone_number" type="number" class="form-control"  placeholder="nhập số điện thoại" value="{{old('phone_number')}}">
                            <span class="error phone_number_error" style="color: red"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Số CMND</h5></label>
                            <input name="cmnd" type="text" class="form-control"  placeholder="nhập số chứng minh nhân dân">
                            <span class="error cmnd_error" style="color: red"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Quê Quán</h5></label>
                            <input name="province" type="text" class="form-control"  placeholder="nhập quê quán">
                            <span class="error province_error" style="color: red"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Địa Chỉ</h5></label>
                            <input name="address" type="text" class="form-control"  placeholder="nhập địa chỉ">
                            <span class="error address_error" style="color: red"></span>

                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for=""><h5>Ngày Sinh</h5></label>
                            <input name="dateOfBirth" type="date" class="form-control datepicker" >
                            <span class="error dateOfBirth_error" style="color: red"></span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for=""><h5>Giới Tính</h5></label>
                            <select name="gender" class="form-control">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                            <span class="error gender_error" style="color: red"></span>
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Khoá số</h5></label>
                            <input name="yearOfAdmission" type="number" class="form-control"  placeholder="nhập số khoá">
                            <span class="error yearOfAdmission_error" style="color: red"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Khoa</h5></label>
                            <input disabled type="text" class="form-control"  value="{{$facultyById->faculty_name}}">
                            <input name="faculty_id" type="hidden" value="{{$facultyById->id}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add_student" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js-add')
<script>
    //add new class jax
    $("#add_form").submit(function (e){
        e.preventDefault();
        const fd= new FormData(this);
        $("#add_btn").text('Đang thêm...');
        $('.error').text('')
        $.ajax({
            url: '{{ route('students.store') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res){

                if(res.status === 200){
                    Swal.fire(
                        'Đã Thêm!',
                        'Thêm sinh viên thành công',
                        'success'
                    )
                    fetchAll();
                    $("#add_btn").text('Thêm');
                    $("#add_form")[0].reset();
                    $("#close_modal_add_student").click();
                }else if(res.status === 500){

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



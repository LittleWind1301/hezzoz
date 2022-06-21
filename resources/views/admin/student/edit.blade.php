<div class="modal fade " id="editStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cập Nhật Thông Tin Sinh Viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_student_form"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="id" name="id">
                <div class="modal-body" style="text-align: left">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4"><h5>Email</h5></label>
                            <input name="email" id="email" type="email" class="form-control" placeholder="nhập email">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4"><h5>Password</h5></label>
                            <input name="password" id="password" type="password" class="form-control" placeholder="nhập mật khẩu">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Mã Sinh Viên</h5></label>
                            <input name="student_id" id="student_id" type="text" class="form-control"  placeholder="nhập mã sinh viên">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Họ Và Tên</h5></label>
                            <input name="name" id="name" type="text" class="form-control"  placeholder="nhập họ và tên">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 ">
                            <label for=""><h5>Số Điện Thoại</h5></label>
                            <input name="phoneNumber" id="phoneNumber" type="number" class="form-control"  placeholder="nhập số điện thoại">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Số CMND</h5></label>
                            <input name="cmnd" id="cmnd" type="text" class="form-control"  placeholder="nhập số chứng minh nhân dân">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Quê Quán</h5></label>
                            <input name="province" id="province" type="text" class="form-control"  placeholder="nhập quê quán">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Địa Chỉ</h5></label>
                            <input name="address" id="address" type="text" class="form-control"  placeholder="nhập địa chỉ">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for=""><h5>Ngày Sinh</h5></label>
                            <input name="dateOfBirth" id="dateOfBirth" type="date" class="form-control datepicker" >
                        </div>
                        <div class="form-group col-md-4">
                            <label for=""><h5>Giới Tính</h5></label>
                            <select name="gender" class="form-control gender">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Năm Vào Trường</h5></label>
                            <input name="yearOfAdmission" id="yearOfAdmission" type="number" class="form-control"  placeholder="nhập năm vào trường">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Khoa</h5></label>
                            <input disabled type="text" class="form-control"  value="{{$facultyById->faculty_name}}">
                            <input name="faculty_id" type="hidden" value="{{$facultyById->id}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_edit_student" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="edit_btn">Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js-edit')
<script>


    //edit class ajax request
    $(document).on('click', '.editIcon', function (e){
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{route('students.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                $("#id").val(res.id)
                $("#email").val(res.user.email)
                $("#password").val(res.user.password)
                $("#student_id").val(res.student_id)
                $("#name").val(res.student_name)
                $("#phoneNumber").val(res.phoneNumber)
                $("#address").val(res.address)
                $("#password").val(res.password)
                $("#dateOfBirth").val(res.dateOfBirth)
                $(".gender").val(res.gender)
                $('#province').val(res.province)
                $('#cmnd').val(res.cmnd)
                $('#yearOfAdmission').val(res.yearOfAdmission)

            }
        })
    })

    //update student ajax request
    $("#edit_student_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Đang cập nhật...');
        $.ajax({
            url: '{{ route('students.update') }}',
            method: 'post',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            success: function (res){
                console.log(res)
                if(res.status == 200){
                    Swal.fire(
                        'Đã Thay Đổi',
                        'Dữ liệu đã được thay đổi thành công',
                        'success'
                    )
                    fetchAll();
                    $("#edit_btn").text('Thay Đổi');
                    $("#edit_student_form")[0].reset();
                    $("#close_modal_edit_student").click();
                }

            }
        })
    });
</script>

@endsection




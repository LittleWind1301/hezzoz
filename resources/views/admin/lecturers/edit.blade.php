<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Giảng Viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_form"  enctype="multipart/form-data" class="needs-validation-add" novalidate>
                @csrf
                <div class="modal-body" style="text-align: left">

                    <div class="alert alert-danger text-center msg" style="display: none">Có lỗi xảy ra, vui lòng thử lại!</div>

                    <input type="hidden" value="" id="id" name="id">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4"><h5>Email</h5></label>
                            <input id="email" name="email" type="email" class="form-control" placeholder="nhập email" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4"><h5>Password</h5></label>
                            <input id="password" name="password" type="password" class="form-control" placeholder="nhập mật khẩu" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Mã Giảng Viên</h5></label>
                            <input id="lecturers_id" name="lecturers_id" type="text" class="form-control"  placeholder="nhập mã giảng viên" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Họ Và Tên</h5></label>
                            <input id="name" name="name" type="text" class="form-control"  placeholder="nhập họ và tên" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 ">
                            <label for=""><h5>Số Điện Thoại</h5></label>
                            <input id="phoneNumber" name="phoneNumber" type="number" class="form-control"  placeholder="nhập số điện thoại" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Số CMND</h5></label>
                            <input id="cmnd" name="cmnd" type="text" class="form-control"  placeholder="nhập số chứng minh nhân dân" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Quê Quán</h5></label>
                            <input id="province" name="province" type="text" class="form-control"  placeholder="nhập quê quán" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Địa Chỉ</h5></label>
                            <input id="address" name="address" type="text" class="form-control"  placeholder="nhập địa chỉ" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Ngày Sinh</h5></label>
                            <input id="dateOfBirth" name="dateOfBirth" type="date" class="form-control datepicker" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Giới Tính</h5></label>
                            <select name="gender" class="form-control gender" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label for=""><h5>Bộ môn</h5></label>
                            <input disabled type="text" class="form-control"  value="{{$courseById->course_name}}">
                            <input name="course_id" type="hidden" value="{{$courseById->id}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_edit" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="edit_btn">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('js-edit')
    <script>

        $(document).on('click', '.editIcon', function (e){
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{route('lecturers.edit')}}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (res){
                    $("#id").val(res.id)
                    $("#email").val(res.user.email)
                    $("#password").val(res.user.password)
                    $("#lecturers_id").val(res.lecturers_id)
                    $("#name").val(res.lecturers_name)
                    $("#phoneNumber").val(res.phoneNumber)
                    $("#address").val(res.address)
                    $("#password").val(res.password)
                    $("#dateOfBirth").val(res.dateOfBirth)
                    $(".gender").val(res.gender)
                    $('#province').val(res.province)
                    $('#cmnd').val(res.cmnd)
                }
            })
        })

        $("#edit_form").submit(function (e){
            e.preventDefault();
            const fd = new FormData(this);
            $("#edit_btn").text('Đang cập nhật...');
            $.ajax({
                url: '{{ route('lecturers.update') }}',
                method: 'post',
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                success: function (res){
                    if(res.status == 200){
                        Swal.fire(
                            'Đã Thay Đổi',
                            'Dữ liệu đã được thay đổi thành công',
                            'success'
                        )
                        fetchAll();
                        $("#edit_btn").text('Thay Đổi');
                        $("#edit_form")[0].reset();
                        $("#close_modal_edit").click();
                    }else if(res.status == 500){
                        $('#msg').show()
                        console.log(res)
                    }

                }
            })
        });
    </script>

    <script>
        // Disable form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Get the forms we want to add validation styles to
                var forms = document.getElementsByClassName('needs-validation-add');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
@endsection








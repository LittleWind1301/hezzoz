<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Giảng Viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form"  enctype="multipart/form-data" class="needs-validation-add" novalidate>
                @csrf
                <div class="modal-body" style="text-align: left">

                    <div class="alert alert-danger text-center msg" style="display: none">Có lỗi xảy ra, vui lòng thử lại!</div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4"><h5>Email</h5></label>
                            <input name="email" type="email" class="form-control" placeholder="nhập email" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4"><h5>Password</h5></label>
                            <input name="password" type="password" class="form-control" placeholder="nhập mật khẩu" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Mã Giảng Viên</h5></label>
                            <input name="lecturers_id" type="text" class="form-control"  placeholder="nhập mã giảng viên" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Họ Và Tên</h5></label>
                            <input name="name" type="text" class="form-control"  placeholder="nhập họ và tên" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 ">
                            <label for=""><h5>Số Điện Thoại</h5></label>
                            <input name="phoneNumber" type="number" class="form-control"  placeholder="nhập số điện thoại" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Số CMND</h5></label>
                            <input name="cmnd" type="text" class="form-control"  placeholder="nhập số chứng minh nhân dân" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Quê Quán</h5></label>
                            <input name="province" type="text" class="form-control"  placeholder="nhập quê quán" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Địa Chỉ</h5></label>
                            <input name="address" type="text" class="form-control"  placeholder="nhập địa chỉ" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=""><h5>Ngày Sinh</h5></label>
                            <input name="dateOfBirth" type="date" class="form-control datepicker" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><h5>Giới Tính</h5></label>
                            <select name="gender" class="form-control" required>
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
                    <button type="button" class="btn btn-secondary" id="close_modal_add" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Lưu</button>
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
        $("#add_btn").text('Đang lưu...');

        $.ajax({
            url: '{{ route('lecturers.store') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res){

                if(res.status == 200){
                    Swal.fire(
                        'Đã Thêm!',
                        res.messages,
                        'success'
                    )
                    fetchAll();
                    $("#add_btn").text('Lưu');
                    $("#add_form")[0].reset();
                    $("#close_modal_add").click();
                }else if(res.status == 500){
                    console.log(res)
                    $('#msg').show()
                }
            }
        })
    })
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




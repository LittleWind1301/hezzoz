<div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cập Nhật Lớp Học Phần</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_class_form" enctype="multipart/form-data" class="needs-validation-edit" novalidate>
                @csrf

                <input type="hidden" value="" name="class_id" id="class_id">
                <div class="modal-body" align="left">
                    <div class="alert alert-danger text-center msg" style="display: none">Có lỗi xảy ra, vui lòng thử lại!</div>
                    <div class="form-group">
                        <label for="">Tên lớp học phần:</label>
                        <input type="text" class="form-control" id="class_name" placeholder="" name="class_name" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Mã lớp học phần:</label>
                        <input type="text" class="form-control" id="class_code" placeholder="" name="class_code" required>
                        <div class="invalid-feedback">Please fill out this field.</div>
                        <span  style="color: red" class="error class_code_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="uname">Khoá:</label>
                        <input type="text" class="form-control" id="courseNumber" placeholder="" name="courseNumber" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Học kì:</label>
                        <select type="text" class="form-control semester" id="" name="semester" required>
                            <option value="">---Chọn học kì---</option>
                            <option value="2_2026_2027">2_2026_2027</option>
                            <option value="1_2026_2027">1_2026_2027</option>
                            <option value="2_2025_2026">2_2025_2026</option>
                            <option value="1_2025_2026">1_2025_2026</option>
                            <option value="2_2024_2025">2_2024_2025</option>
                            <option value="1_2024_2025">1_2024_2025</option>
                            <option value="2_2023_2024">2_2023_2024</option>
                            <option value="1_2023_2024">1_2023_2024</option>
                            <option value="2_2022_2023">2_2022_2023</option>
                            <option value="1_2022_2023">1_2022_2023</option>
                            <option value="2_2021_2022">2_2021_2022</option>
                            <option value="1_2021_2022">1_2021_2022</option>
                            <option value="2_2020_2021">2_2020_2021</option>
                            <option value="1_2020_2021">1_2020_2021</option>
                        </select>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>


                    <div class="form-group">
                        <label for="">Học phần:</label>
                        <input disabled type="text" class="form-control" value="{{$subject->subject_code}} - {{$subject->subject_name}}" >
                        <input type="hidden" class="form-control" value="{{$subject->id}}" name="subject_id">
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_edit_class" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="edit_btn">Lưu</button>
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
            url: '{{route('classes.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                $("#class_id").val(res.id);
                $('#class_code').val(res.class_code)
                $("#class_name").val(res.class_name)
                $('#courseNumber').val(res.courseNumber)
                $('.semester').val(res.semester)
            }
        })
    })

    //update class ajax request
    $("#edit_class_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_class_btn").text('Đang Lưu...');
        $.ajax({
            url: '{{ route('classes.update') }}',
            method: 'post',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            success: function (res){
                if(res.status == 200){
                    Swal.fire(
                        'Đã Thay Đổi',
                        res.messages,
                        'success'
                    )
                    fetchAll();
                    $("#edit_btn").text('Lưu');
                    $("#edit_class_form")[0].reset();
                    $("#close_modal_edit_class").click();
                }else if(res.status == 500){
                    console.log(res.messages)
                    $('.msg').show()
                }
            },
            error:function (error){
                let  errorResponse = error.responseJSON.errors

                if(Object.keys(errorResponse).length > 0){
                    $('.msg').show()
                    for(let key in errorResponse){
                        $('.' + key + '_error').text(errorResponse[key])
                    }
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
            var forms = document.getElementsByClassName('needs-validation-edit');
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



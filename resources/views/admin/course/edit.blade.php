<div class="modal fade " id="editCourseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Chỉnh Sửa Thông Tin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST" id="edit_form"  enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="alert alert-danger text-center msg" style="display: none">Có lỗi xảy ra, Vui lòng kiểm tra lại!</div>
                    <input type="hidden" id="course_id" name="course_id">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label float-left">Mã Bộ Môn:</label>
                        <input type="text" class="form-control" name="course_code" id="course_code">
                        <span  style="color: red" class="error course_code_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label float-left">Tên Bộ Môn:</label>
                        <input type="text" class="form-control" name="course_name" id="course_name">
                        <span  style="color: red" class="error course_name_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_edit_course" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="edit_course_btn">Lưu</button>
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
        $('.error').text('')
        $('.msg').hide()
        $.ajax({
            url: '{{route('courses.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                $('#course_id').val(res.id)
                $('#course_code').val(res.course_code);
                $('#course_name').val(res.course_name);
            }
        })
    })

    //update class ajax request
    $("#edit_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_course_btn").text('Đang cập nhật...');
        $.ajax({
            url: '{{ route('courses.update') }}',
            method: 'post',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            dataType:   'json',
            success: function (res){

                if(res.status == 200){
                    Swal.fire(
                        'Đã Thay Đổi',
                        'Dữ liệu đã được thay đổi thành công',
                        'success'
                    )
                    fetchAll();
                    $("#edit_course_btn").text('Lưu');
                    $("#edit_form")[0].reset();
                    $("#close_modal_edit_course").click();
                }
            },
            error:function (error){
                console.log(error)
                $('.msg').show()
                let  errorResponse = error.responseJSON.errors

                console.log(errorResponse)

                if(Object.keys(errorResponse).length > 0){
                    $('.msg').text(errorResponse.msg)
                    for(let key in errorResponse){
                        $('.' + key + '_error').text(errorResponse[key])
                    }
                }
            }
        })
    });
</script>
@endsection



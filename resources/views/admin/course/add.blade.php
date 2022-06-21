<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Bộ Môn Mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="faculty_id" name="faculty_id" value="{{$facultyById->id}}">
                <div class="modal-body" align="left">

                    <div class="alert alert-danger text-center msg" style="display: none"></div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label float-left">Mã Bộ Môn:</label>
                        <input type="text" class="form-control"  name="course_code">
                        <span  style="color: red" class="error course_code_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label float-left">Tên Bộ Môn:</label>
                        <input type="text" class="form-control"  name="course_name">
                        <span  style="color: red" class="error course_name_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add_course" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_course_btn">Thêm</button>
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
        $("#add_course_btn").text('Đang Thêm...');
        $('.error').text('')
        $.ajax({
            url: '{{ route('courses.store') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res){
                console.log(res)
                if(res.status == 200){
                    Swal.fire(
                        'Đã thêm',
                        'Tạo bộ môn mới thành công',
                        'success')
                    $("#add_course_btn").text('Thêm');
                    $("#add_form")[0].reset();
                    $("#close_modal_add_course").click();
                    fetchAll();
                }
            },
            error:function (error){
                $('.msg').show()

                let  errorResponse = error.responseJSON.errors

                if(Object.keys(errorResponse).length > 0){
                    $('.msg').text(errorResponse.msg)
                    for(let key in errorResponse){
                        $('.' + key + '_error').text(errorResponse[key])
                    }
                }
            }
        })
    })
</script>
@endsection




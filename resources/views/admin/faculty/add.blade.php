<div class="modal fade" id="addFacultyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tạo Khoa Mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body" align="left">

                    <div class="alert alert-danger text-center msg_add" style="display: none"></div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Mã Khoa:</label>
                        <input type="text" class="form-control" id="recipient-name" name="faculty_code">
                        <span  style="color: red" class="error_add faculty_code_error_add"></span>
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Tên Khoa:</label>
                        <input type="text" class="form-control" id="recipient-name" name="faculty_name">
                        <span style="color: red" class="error_add faculty_name_error_add"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add_faculty" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_faculty_btn">Thêm</button>
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
        $("#add_faculty_btn").text('Đang Thêm...');
        $('.error_add').text('')
        $('.msg_add').hide()
        $.ajax({
            url: '{{ route('faculties.store') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res){
                if(res.status === 200){
                    Swal.fire(
                        'Đã thêm',
                        'Tạo khoa mới thành công',
                        'success')
                    fetchAll()
                    $("#add_faculty_btn").text('Thêm');
                    $("#add_form")[0].reset();
                    $("#close_modal_add_faculty").click();
                }
            },
            error:function (error){
                $('.msg_add').show()

                let  errorResponse = error.responseJSON.errors

                if(Object.keys(errorResponse).length > 0){
                    $('.msg_add').text(errorResponse.msg)
                    for(let key in errorResponse){
                        $('.' + key + '_error_add').text(errorResponse[key])
                    }
                }
            }
        })
    })
</script>
@endsection




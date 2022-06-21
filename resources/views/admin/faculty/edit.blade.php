<div class="modal fade " id="editFacultyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Chỉnh Sửa Thông Tin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="faculty_id" name="faculty_id">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label float-left">Mã Khoa:</label>
                        <input type="text" class="form-control" name="faculty_code" id="faculty_code">
                        <span  style="color: red" class="error faculty_code_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label float-left">Tên Khoa:</label>
                        <input type="text" class="form-control" name="faculty_name" id="faculty_name">
                        <span  style="color: red" class="error faculty_name_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_edit_faculty" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="edit_faculty_btn">Lưu</button>
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
        $.ajax({
            url: '{{route('faculties.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                $('#faculty_id').val(res.id)
                $('#faculty_code').val(res.faculty_code);
                $('#faculty_name').val(res.faculty_name);
            }
        })
    })

    //update class ajax request
    $("#edit_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_faculty_btn").text('Đang cập nhật...');
        $.ajax({
            url: '{{ route('faculties.update') }}',
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
                    $("#edit_faculty_btn").text('Lưu');
                    $("#edit_form")[0].reset();
                    $("#close_modal_edit_faculty").click();
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




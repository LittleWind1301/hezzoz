<div class="modal fade " id="editSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Đổi môn học</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_subject_form"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="subject_id" id="subject_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Mã Môn Học:</label>
                        <input type="text" class="form-control" id="subject_code"  name="subject_code">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Tên Môn Học:</label>
                        <input type="text" class="form-control" id="subject_name"  name="subject_name">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Số Tín Chỉ:</label>
                        <input type="number" class="form-control" id="numCredit"  name="numCredit">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal" data-dismiss="modal">Đóng</button>
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
            url: '{{route('subjects.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                $("#subject_id").val(res.id);
                $("#subject_name").val(res.subject_name)
                $('#subject_code').val(res.subject_code)
                $('#numCredit').val(res.numCredit)
            }
        })
    })

    //update class ajax request
    $("#edit_subject_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Đang cập nhật...');
        $.ajax({
            url: '{{ route('subjects.update') }}',
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
                    $("#edit_subject_form")[0].reset();
                    $("#close_modal").click();
                }
            }
        })
    });
</script>
@endsection




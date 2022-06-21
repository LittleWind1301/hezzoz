<div class="modal fade " id="editGrQuestModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Đổi môn học</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_form"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="groupQuestionId" id="groupQuestionId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="" class="col-form-label"><h5>Tên Nhóm</h5></label>
                        <input type="text" class="form-control" id="name" name="name">
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
    //edit ajax request
    $(document).on('click', '.editIcon', function (e){
        e.preventDefault()
        let id = $(this).attr('id');
        $.ajax({
            url: '{{route('groupQuestions.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                $("#name").val(res.name);
                $("#groupQuestionId").val(res.id);
            }
        })
    })

    //update ajax request
    $("#edit_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Đang cập nhật...');
        $.ajax({
            url: '{{ route('groupQuestions.update') }}',
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
                    $("#edit_btn").text('Thay Đổi');
                    $("#edit_form")[0].reset();
                    $("#close_modal").click();
                    fetchAll();
                }
            }
        })
    });
</script>
@endsection




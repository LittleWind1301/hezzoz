<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tạo Nhóm Câu Hỏi Mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_group_question_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="text-align: left">
                    <input type="hidden" value="{{$subjectById->id}}" name="subject_id">
                    <div class="form-group">
                        <label for="name" class="col-form-label"><h5>Tên Nhóm</h5></label>
                        <input type="text" class="form-control" id="" name="name">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add_group_question" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('js-add')
<script>
    $("#add_group_question_form").submit(function (e){
        e.preventDefault();
        const fd= new FormData(this);
        $("#add_btn").text('Đang Thêm...');
        $.ajax({
            url: '{{ route('groupQuestions.store') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res){
                if(res.status == 200){
                    Swal.fire(
                        'Đã thêm',
                        'Tạo nhóm câu hỏi mới thành công',
                        'success')
                    $("#add_btn").text('Thêm');
                    $("#add_group_question_form")[0].reset();
                    $("#close_modal_add_group_question").click();
                }

                fetchAll();
            }
        })
    })
</script>
@endsection



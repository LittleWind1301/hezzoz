<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Học Phần Mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="course_id" name="course_id" value="{{$courseById->id}}">
                <div class="modal-body" style="text-align: left">

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Mã Học Phần:</label>
                        <input type="text" class="form-control"  name="subject_code">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Tên Học Phần:</label>
                        <input type="text" class="form-control"  name="subject_name">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Số Tín Chỉ:</label>
                        <input type="number" class="form-control"  name="numCredit">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add_subject" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Thêm</button>
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
        $("#add_btn").text('Đang Thêm...');
        $.ajax({
            url: '{{ route('subjects.store') }}',
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
                        'Tạo học phần mới thành công',
                        'success')
                    $("#add_btn").text('Thêm');
                    $("#add_form")[0].reset();
                    $("#close_modal_add_subject").click();
                    fetchAll();
                }
            }
        })
    })
</script>

@endsection




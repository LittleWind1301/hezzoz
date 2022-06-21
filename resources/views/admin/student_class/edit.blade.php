<div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="student_class_id" id="student_class_id">
                <div class="modal-body">
                    <div class="form-group ">
                        <label for="className" class="float-left">Tên lớp</label>
                        <select name="class" class="form-control class_id">
                            @foreach($clazzes as $item)
                                <option value="{{$item->id}}" name="{{$item->id}}">{{$item->class_name}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group ">
                        <label for="subjectName" class="float-left">Học Viên</label>
                        <select name="student" class="form-control student_id" >
                            @foreach($students as $item)
                                <option id="{{$item->id}}" value="{{$item->id}}">Mã SV: {{$item->id}} - {{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_edit_modal" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="edit_btn">Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js-edit')
<script>

    //edit class ajax request
    $(document).on('click', '.editIcon', function (e) {
        e.preventDefault();
        let id = $(this).attr('id');
        var select = document.querySelector('select.class_id')
        $.ajax({
            url: '{{route('student_classes.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                $('#student_class_id').val(res.id)
                $('.class_id').val(res.class_id);
                $('.student_id').val(res.student_id);
            }
        })
    })

    //update class ajax request
    $("#edit_form").submit(function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Đang cập nhật...');
        $.ajax({
            url: '{{ route('student_classes.update') }}',
            method: 'post',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            success: function (res) {
                if(res.status == 200){
                    Swal.fire(
                        'Đã Thay Đổi',
                        'Dữ liệu đã được thay đổi thành công',
                        'success'
                    )
                    fetchAll();
                }
                $("#edit_btn").text('Thay Đổi');
                    $("#close_edit_modal").click();
                    $("#edit_form")[0].reset();
            }
        })
    });
</script>

@endsection




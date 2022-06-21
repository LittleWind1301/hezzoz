<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Sinh Viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{$classById->id}}" name="class_id">
                <div class="modal-body" >
                    <div id="listStudent"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_add_modal" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js-add')
<script>
    $(document).on('click', '.createIcon', function (e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{route('student_classes.create')}}',
            method: 'get',
            data: {
                class_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                $("#listStudent").html(res)
                $(document).ready( function () {
                    $('#mytable').DataTable();
                });
            }
        })
    })


    //asign subject to class
    $("#add_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("add_btn").text('Đang Thêm...');
        $.ajax({
            url:'{{route('student_classes.store')}}',
            method:'post',
            data: fd,
            cache:false,
            contentType: false,
            processData:false,
            success: function (res){

                if(res.status == 200){
                    Swal.fire(
                        'Đã Thêm',
                        'Thêm sinh viên lớp thành công',
                        'success'
                    )
                    fetchAll()
                    $("add_btn").text('Thêm');
                    $('#close_add_modal').click()
                    $('#add_form')[0].reset()
                }

            }
        })
    })
</script>

@endsection




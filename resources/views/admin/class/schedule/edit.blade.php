<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tạo Lịch Thi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_form" enctype="multipart/form-data" class="needs-validation-edit" novalidate>
                @csrf
                <div class="modal-body" align="left">
                    <div class="alert alert-danger text-center msg" style="display: none">Có lỗi xảy ra, vui lòng thử lại!</div>

                    <input type="hidden" name="schedule_id" value="" id="schedule_id">
                    <div class="form-group">
                        <label for="">Tiêu đề:</label>
                        <input type="text" class="form-control" placeholder="" id="title" name="title" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Thời gian bắt đầu:</label>
                        <input type="datetime-local" class="form-control" id="timeStart" placeholder="" name="timeStart" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Thời gian kết thúc:</label>
                        <input type="datetime-local" class="form-control" id="timeFinish" placeholder="" name="timeFinish" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Thời gian thi(phút):</label>
                        <input min="0" type="number" class="form-control" id="limitTime" placeholder="" name="limitTime" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Thời gian thu bài(phút):</label>
                        <input min="0" type="number" class="form-control" id="extraTime" placeholder="" name="extraTime" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Lớp học phần:</label>
                        <input disabled type="text" class="form-control" value="{{$classById->class_code}}" >
                        <input type="hidden" class="form-control" value="{{$classById->id}}" name="class_id">
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_edit" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="edit_btn">Lưu</button>
                </div>
            </form>

        </div>
    </div>
</div>

@section('js-edit')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>
<script>


    //edit class ajax request
    $(document).on('click', '.editIcon', function (e){
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{route('schedules.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){

                $('#schedule_id').val(res.id)
                $('#title').val(res.title)
                $('#limitTime').val(res.limitTime)
                $('#extraTime').val(res.extraTime)
                $('#timeStart').val(moment.utc(res.timeStart).format().substring(0,19))
                $('#timeFinish').val(moment.utc(res.timeFinish).format().substring(0,19))

            }
        })
    })

    //update class ajax request
    $("#edit_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Đang Lưu...');
        $.ajax({
            url: '{{ route('schedules.update') }}',
            method: 'post',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            success: function (res){
                console.log(res)
                if(res.status == 200){
                    Swal.fire(
                        'Đã Cập Nhật',
                        res.messages,
                        'success'
                    )
                    fetchAll();
                    $("#edit_btn").text('Lưu');
                    $("#edit_form")[0].reset();
                    $("#close_modal_edit").click();
                }else if(res.status == 500){
                    console.log(res.messages)
                    $('.msg').show()
                }
            },
            error:function (error){
                let  errorResponse = error.responseJSON.errors

                if(Object.keys(errorResponse).length > 0){
                    $('.msg').show()
                    for(let key in errorResponse){
                        $('.' + key + '_error').text(errorResponse[key])
                    }
                }

            }
        })
    });
</script>

<script>
    // Disable form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Get the forms we want to add validation styles to
            var forms = document.getElementsByClassName('needs-validation-edit');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

@endsection



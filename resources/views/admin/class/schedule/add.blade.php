<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tạo Lịch Thi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form" enctype="multipart/form-data" class="needs-validation-add" novalidate>
                @csrf
                <div class="modal-body" align="left">
                    <div class="alert alert-danger text-center msg" style="display: none">Có lỗi xảy ra, vui lòng thử lại!</div>

                    <div class="form-group">
                        <label for="">Tiêu đề:</label>
                        <input type="text" class="form-control" placeholder="" name="title" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Thời gian bắt đầu:</label>
                        <input type="datetime-local" class="form-control" id="" placeholder="" name="timeStart" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Thời gian kết thúc:</label>
                        <input type="datetime-local" class="form-control" placeholder="" name="timeFinish" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Thời gian thi(phút):</label>
                        <input min="0" type="number" class="form-control"  placeholder="" name="limitTime" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group">
                        <label for="">Thời gian thu bài(phút):</label>
                        <input min="0" type="number" class="form-control"  placeholder="" name="extraTime" required>
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
                    <button type="button" class="btn btn-secondary" id="close_modal_add" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Lưu</button>
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
        $("#add_btn").text('Đang Lưu...');

        $.ajax({
            url: '{{ route('schedules.store') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res){
                console.log(res)
                if(res.status == 200){
                    Swal.fire(
                        'Đã Lưu',
                        res.messages,
                        'success')

                    $("#add_btn").text('Lưu')
                    $("#add_form")[0].reset()
                    $("#close_modal_add").click()
                    fetchAll()

                }else if(res.status == 500){
                    console.log(res)
                    $('.msg').show()
                }
            }
        })
    })
</script>


<script>
    // Disable form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Get the forms we want to add validation styles to
            var forms = document.getElementsByClassName('needs-validation-add');
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


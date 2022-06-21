<div class="modal fade" id="addAccountFacultyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tạo Tài Khoản quản trị </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_account_faculty_form"  enctype="multipart/form-data">
                @csrf
                <input type="hidden"  name="faculty_id" value="{{$facultyById->id}}">
                <div class="modal-body" style="text-align: left">

                    <div class="form-group">
                        <label for="" class="col-form-label"><h5>Email:</h5></label>
                        <input type="email" class="form-control" name="user_email">
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label"><h5>Password:</h5></label>
                        <input type="password" class="form-control"  name="user_pwd">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add_account_faculty" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_account_faculty_btn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('js-add')
<script>

    $("#add_account_faculty_form").submit(function (e){
        e.preventDefault();
        const fd= new FormData(this);
        $("#add_account_faculty_btn").text('Đang Thêm...');
        $.ajax({
            url: '{{ route('accounts.storeAccountFaculty') }}',
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
                        'Tạo tài khoản mới thành công',
                        'success')
                    $("#add_account_faculty_btn").text('Thêm');
                    $("#add_account_faculty_form")[0].reset();
                    $("#close_modal_add_account_faculty").click();
                    fetchAll();
                }
            }
        })
    })
</script>

@endsection



<div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Đổi tên lớp học</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="edit_role_form"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="role_id" id="role_id">
                <div class="modal-body" style="text-align: left">
                    <div class="form-group">
                        <label for="" class="col-form-label">Tên Chức Vụ:</label>
                        <input type="text" class="form-control" id="name"  name="name" value="{{old('name')}}">
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label">Mô Tả Chức Vụ:</label>
                        <textarea type="text" class="form-control" id="display_name" name="display_name">{{old('display_name')}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label">
                            <input type="checkbox" class="check-all"  name="" value="">
                            all
                        </label>
                    </div>
                    <div class="form-group">
                        @foreach($permissionsParent as $item)
                        <div class="card border-primary mb-3 col-md-12 card-wrapper">
                            <div class="card-header" style="background-color: #c2c2d6">
                                <label for="" >
                                    <input type="checkbox" value="" class="checkbox_wrapper_edit">
                                </label>
                                Module {{$item->name}}
                            </div>
                            <div class="row">
                                @foreach($item->permissionsChildren as $permissionsChildren)
                                    <div class="card-body text-primary col-md-3 arr_input">
                                        <h5 class="card-title">
                                            <label for="">
                                                <input type="checkbox" 
                                                    class="checkbox_children_edit"
                                                    name="permission_id[]" 
                                                    value="{{$permissionsChildren->id}}">
                                            </label>
                                            {{ $permissionsChildren->name}}
                                        </h5>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_edit_role" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="edit_btn">Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
<script>

    $('.checkbox_wrapper_edit').on('click', function(){
        
        $(this).parents('.card-wrapper').find('.checkbox_children_edit').prop('checked', $(this).prop('checked'));
    });

    $('.check-all').on('click', function(){
        $(this).parents().find('.checkbox_children_edit').prop('checked', $(this).prop('checked'));
        $(this).parents().find('.checkbox_wrapper_edit').prop('checked', $(this).prop('checked'));
        
    })
    //edit class ajax request
    $(document).on('click', '.editIcon', function (e){
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{route('roles.edit')}}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                console.log(res)
                $('#role_id').val(res.roleById.id)
                $('#name').val(res.roleById.name);
                $('#display_name').val(res.roleById.display_name);

                $('.arr_input').find(':checkbox[name="permission_id[]"]').each(function () {
                    
                    for(let i=0; i<res.permissionsChecked.length; i++){
                        if($(this).val() == res.permissionsChecked[i].id){
                            $(this).prop('checked', true)
                        }
                    }
                    
                });
            }
        })
    })

    //update class ajax request
    $("#edit_role_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Đang cập nhật...');
        $.ajax({
            url: '{{ route('roles.update') }}',
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
                    $("#edit_btn").text('Thay Đổi');
                    $("#edit_role_form")[0].reset();
                    $("#close_modal_edit_role").click();
                }
            }
        })
    });
</script>




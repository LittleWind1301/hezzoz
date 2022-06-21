<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Chức Vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="text-align: left">
                    <div class="form-group">
                        <label for="" class="col-form-label">Tên Chức Vụ:</label>
                        <input type="text" class="form-control"  name="name" value="{{old('name')}}">
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label">Mô Tả Chức Vụ:</label>
                        <textarea type="text" class="form-control" id="" name="display_name">{{old('display_name')}}</textarea>
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
                                    <input type="checkbox" value="" class="checkbox_wrapper">
                                </label>
                                Module {{$item->name}}
                            </div>
                            <div class="row">
                                @foreach($item->permissionsChildren as $permissionsChildren)
                                    <div class="card-body text-primary col-md-3">
                                        <h5 class="card-title">
                                            <label for="">
                                                <input type="checkbox" 
                                                    class="checkbox_children"
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
                    <button type="button" class="btn btn-secondary" id="close_modal_add_role" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js'></script>
<script>

    $('.checkbox_wrapper').on('click', function(){
        
        $(this).parents('.card-wrapper').find('.checkbox_children').prop('checked', $(this).prop('checked'));
    });

    //checkbox all
    $('.check-all').on('click', function(){
        $(this).parents().find('.checkbox_children').prop('checked', $(this).prop('checked'));
        $(this).parents().find('.checkbox_wrapper').prop('checked', $(this).prop('checked'));
    })

    //add new class jax
    $("#add_form").submit(function (e){
        e.preventDefault();
        const fd= new FormData(this);
        $("#add_class_btn").text('Adding...');
        $.ajax({
            url: '{{ route('roles.store') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res){
                if(res.status == 200){
                    Swal.fire(
                        'Đã thêm',
                        'Tạo chức vụ mới thành công',
                        'success')
                $("#add_btn").text('Thêm');
                $("#add_form")[0].reset();
                $("#close_modal_add_role").click();
                fetchAll();
                }
            }
        })
    })
</script>




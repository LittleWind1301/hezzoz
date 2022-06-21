<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

    <div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Chức Vụ', 'key'=>'Danh Sách'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Danh Sách Chức Vụ</div>
                                <div class="col" align="right">
                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addRoleModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    @include('admin.role.add')
                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="Show_all_roles">
                            <h1 class="text-center text-secondary my-5">Đang Tải...</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.role.edit')

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>

    <script>
        //fetch all class
        fetchAll();
        function fetchAll(){
            $.ajax({
                url: '{{route('roles.fetchAll')}}',
                method: 'get',
                success: function (res){
                    $('#Show_all_roles').html(res);
                    $("table").DataTable({
                        order: [0, 'desc']
                    })
                }
            })
        }

        //$("document").on('click', 'iconDelete', function (e){
        function change_status(id){
            Swal.fire({
                title: 'Bạn muốn thay đổi trạng thái này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Thay Đổi'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route('classes.delete')}}',
                        method: 'post',
                        data:{
                            id: id,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (res){
                            console.log(res)
                            Swal.fire(
                                'Đã Thay Đổi!',
                                'Trạng thái đã được thay đổi',
                                'success'
                            )
                            fetchAllClasses();
                        }
                    })

                }
            })
        }
</script>
@endsection

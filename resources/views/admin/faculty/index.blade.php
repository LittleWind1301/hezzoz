<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

    <div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Khoa', 'key'=>'Quản Lí Khoa'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Danh Sách Các Khoa</div>
                                <div class="col" align="right">
                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addFacultyModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    @include('admin.faculty.add')
                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="Show_all_faculty">
                            <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.faculty.edit')
@endsection

@section('js-index')
    <script>
        fetchAll();
        function fetchAll(){
            $.ajax({
                url: '{{route('faculties.fetchAll')}}',
                method: 'get',
                success: function (res){
                    $('#Show_all_faculty').html(res);
                    $("table").DataTable({
                        order: [0, 'desc']
                    })
                }
            })
        }

        function deleteItem(id){
            Swal.fire({
                title: 'Bạn muốn xoá khoa này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('faculties.delete') }}',
                        method: 'post',
                        data:{
                            id: id,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (res){
                            console.log(res)
                            if(res.code==200){
                                Swal.fire(
                                    'Đã Xoá!',
                                    'Xoá dữ liệu thành công!',
                                    'success'
                                )
                                fetchAll();
                            }
                        }
                    })
                }
            })
        }

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
                        url: '{{route('faculties.changeStatus')}}',
                        method: 'post',
                        data:{
                            id: id,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (res){
                            Swal.fire(
                                'Đã Thay Đổi!',
                                'Trạng thái đã được thay đổi',
                                'success'
                            )
                            fetchAll();
                        }
                    })
                }
            })
        }
    </script>
@endsection

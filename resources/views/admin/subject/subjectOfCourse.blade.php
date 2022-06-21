
<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Học Phần</title>
@endsection

@section('content')

    <div class="content-wrapper">
        @include('admin.partials.content-header', ['name'=>'Quản Lý Học Phần', 'key'=>'Quản Lí Học Phần'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Bộ Môn {{$courseById->course_name }} / Danh Sách Học Phần</div>
                                <input type="hidden" id="course_id" value="{{$courseById->id}}">
                                <div class="col" align="right">
                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addSubjectModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    @include('admin.subject.add')
                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="Show_all_subject">
                            <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                        </div>
                    </div>
                </div>
                <a href="{{ URL::previous() }}" class="btn btn-primary">Trở về</a>
            </div>
        </div>
    </div>
    @include('admin.subject.edit')

@endsection
@section('js-index')
    <script>
        fetchAll();
        function fetchAll(){
            var course_id = $('#course_id').val()
            $.ajax({
                url: '{{route('subjects.fetchSubjectOfCourse')}}',
                method: 'get',
                data:{
                    course_id:course_id
                },
                success: function (res){
                    $('#Show_all_subject').html(res);
                    $("table").DataTable({
                        order: [0, 'desc']
                    })
                }
            })
        }

        function deleteItem(id){
            Swal.fire({
                title: 'Bạn muốn xoá môn học này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('subjects.delete') }}',
                        method: 'post',
                        data:{
                            id: id,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (res){
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
    </script>
@endsection

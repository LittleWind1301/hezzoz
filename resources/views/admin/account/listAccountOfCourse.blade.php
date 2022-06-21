<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Tài khoản quản trị khoa</title>
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Bộ môn {{$courseById->course_name}} \ Tài Khoản quản trị</div>
                                <input type="hidden" id="course_id"  value="{{$courseById->id}}">
                                <div class="col" align="right">
                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addAccountFacultyModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    @include('admin.account.addAccountCourse')

                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="Show_all_account_course">
                            <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-index')
    <script>
        fetchAll();
        function fetchAll(){
            var course_id = $('#course_id').val();
            $.ajax({
                url: '{{route('accounts.fetchAccountCourse')}}',
                method: 'get',
                data: {
                    course_id:course_id
                },
                success: function (res){
                    $('#Show_all_account_course').html(res);
                    $("table").DataTable({
                        order: [0, 'desc']
                    })
                }
            })
        }

        function deleteItem(id){
            Swal.fire({
                title: 'Bạn muốn xoá tài khoản này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('accounts.deleteAccountCourse') }}',
                        method: 'post',
                        data:{
                            id: id,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (res){
                            console.log(res)
                            if(res.status==200){
                                Swal.fire(
                                    'Đã Xoá!',
                                    'Xoá tài khoản thành công!',
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

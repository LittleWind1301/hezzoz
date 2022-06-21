<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

    <div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Sinh Viên', 'key'=>'Danh Sách'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Khoa {{$facultyById->faculty_name}} \ Danh Sách Sinh Viên</div>
                                <input type="hidden" id="faculty_id" value="{{$facultyById->id}}">
                                <div class="col" align="right">

                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addStudentModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#importModal">
                                        Import file Excel
                                    </button>

                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="Show_all_student">
                            <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.student.edit')
    @include('admin.student.import')
    @include('admin.student.add')

@endsection

@section('js-index')
    <script>
        fetchAll();
        function fetchAll(){
            var faculty_id = $('#faculty_id').val()
            $.ajax({
                url: '{{route('students.fetchAll')}}',
                method: 'get',
                data:{
                    faculty_id:faculty_id
                },
                success: function (res){
                    $('#Show_all_student').html(res);
                    $("table").DataTable({
                        order: [0, 'desc']
                    })
                }
            })
        }

        function deleteItem(id){
            Swal.fire({
                title: 'Bạn muốn xoá sinh viên này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('students.delete') }}',
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

<script>
    $("#importStudent").submit(function (e){
        e.preventDefault();
        const fd= new FormData(this);
        $("#add_student_btn").text('Đang Thêm...');
        $.ajax({
            url: '{{ route('students.storeImport') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res){
                console.log(res)
                if(res.status === 200){
                    Swal.fire(
                        'Thành Công',
                        res.messages,
                        'success')
                    fetchAll()
                    $("#add_student_btn").text('Thêm');
                    $("#importStudent")[0].reset();
                    $("#close_modal_add").click();

                }else if(res.status === 500){
                    console.log(res.messages)
                    Swal.fire(
                        'Thất bại',
                        res.messages,
                        'error')
                }
                else if(res.status === 0){
                    console.log(res.messages)
                    Swal.fire(
                        'Thất bại',
                        'Có lỗi xảy ra',
                        'error')
                }

            },
            error:function (error){
                let responseJson = error.responseJSON.errors

                if(Object.keys(responseJson).length > 0){
                    for(let key in responseJson){
                        $('.' + key + '_error').text(responseJson[key])
                    }
                }
            }
        })
    })



</script>

@endsection

<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

    <div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Giảng Viên', 'key'=>'Danh Sách'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Bộ môn {{$courseById->course_name}} \ Danh Sách Giảng Viên</div>
                                <input type="hidden" id="course_id" value="{{$courseById->id}}">
                                <div class="col" align="right">

                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addModal">
                                        <i class="fas fa-plus"></i>
                                    </button>

                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#importModal">
                                        Import file Excel
                                    </button>
                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="Show_all_lecturers">
                            <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.lecturers.add')
    @include('admin.lecturers.edit')
    @include('admin.lecturers.import')
@endsection

@section('js-index')

<script>
    fetchAll();
    function fetchAll(){
        var course_id = $('#course_id').val()
        $.ajax({
            url: '{{route('lecturers.fetchAll')}}',
            method: 'get',
            data:{
                course_id:course_id
            },
            success: function (res){
                $('#Show_all_lecturers').html(res);
                $("table").DataTable({
                    order: [0, 'desc']
                })
            }
        })
    }

    function deleteItem(id){
        Swal.fire({
            title: 'Bạn muốn xoá giảng viên này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xoá'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('lecturers.delete') }}',
                    method: 'post',
                    data:{
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (res){
                        if(res.status==200){
                            Swal.fire(
                                'Đã Xoá!',
                                res.messages,
                                'success'
                            )
                            fetchAll();
                        }else if(res.status == 500){
                            console.log(res)
                            Swal.fire(
                                'Xoá thất bại!',
                                res.messages,
                                'error'
                            )
                        }
                    }
                })
            }
        })
    }

</script>

<script>
    $("#importLecturers").submit(function (e){
        e.preventDefault();
        const fd= new FormData(this);
        $("#add_lecturers_btn").text('Đang Thêm...');
        $.ajax({
            url: '{{ route('lecturers.storeImport') }}',
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
                    $("#add_lecturers_btn").text('Thêm');
                    $("#importLecturers")[0].reset();
                    $("#close_modal_import").click();

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

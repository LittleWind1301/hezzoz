<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Lịch thi</title>
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <br>
                <a href="{{ URL::previous() }}" class="btn btn-info">Trở về</a><br><br>
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Lớp học phần: {{$classById->class_code}} - {{$classById->class_name}}
                                        <br><br>Danh Sách Lịch Thi</h5>
                                    <input type="hidden" value="{{$classById->id}}" id="class_id">
                                </div>
                                <div class="col" align="right">

                                    @if(\Illuminate\Support\Facades\Auth::user()->utype == 'EDU' ||
                                        \Illuminate\Support\Facades\Auth::user()->utype == 'MASTER')
                                        <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addModal">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        @include('admin.class.schedule.add')
                                    @endif

                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="Get_all_data">
                            <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.class.schedule.edit')
    @include('admin.class.schedule.addExam')
    @include('admin.class.schedule.viewExam')
@endsection

@section('js-index')
<script>
    fetchAll();
    function fetchAll(){
        var class_id = $('#class_id').val()
        $.ajax({
            url: '{{route('schedules.fetchAll')}}',
            method: 'get',
            data:{
                class_id:class_id
            },
            success: function (res){
                $('#Get_all_data').html(res);
                $("#myTable").DataTable({
                    order: [0, 'desc']
                })
            }
        })
    }

    function deleteItem(id){
        Swal.fire({
            title: 'Bạn muốn xoá lịch thi này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xoá'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('schedules.delete') }}',
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
                        }else if(res.status==500){
                            console.log(res.message)
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

    $(document).on('click', '.getListExam', function (e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{route('schedules.getListExam')}}',
            method: 'get',
            data: {
                schedule_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                $("#listExam").html(res)
                $(document).ready( function () {
                    $('#addExamTable').DataTable();
                });
            }
        })
    })


    $("#add_exam_form").submit(function (e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_exam_btn").text('Đang Thêm...');
        $.ajax({
            url:'{{route('schedules.addExam')}}',
            method:'post',
            data: fd,
            cache:false,
            contentType: false,
            processData:false,
            success: function (res){
                console.log(res)
                if(res.status == 200){
                    Swal.fire(
                        'Đã Thêm',
                        'Thêm đề thi thành công',
                        'success'
                    )
                    fetchAll()
                    $("#add_exam_btn").text('Thêm');
                    $('#close_add_exam_modal').click()
                    $('#add_exam_form')[0].reset()
                }else if(res.status == 500){
                    console.log(res)
                    Swal.fire(
                        'Thất bại',
                        'error'
                    )
                }
            }
        })
    })
</script>

<script>
    $(document).on('click', '.viewExam', function (e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{route('schedules.viewDetailExam')}}',
            method: 'get',
            data: {
                schedule_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                $("#detailExam").html(res)
            }
        })
    })

    function deleteExam(id){
        Swal.fire({
            title: 'Bạn muốn xoá bài thi này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xoá'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('schedules.deleteExam') }}',
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
                            $('#close_view_exam_modal').click()
                            fetchAll();
                        }else if(res.status==500){
                            console.log(res.message)
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
@endsection



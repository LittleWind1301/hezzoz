<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

    <div class="content-wrapper">

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Lịch thi</div>
                                <div class="col" align="right">

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
    @include('admin.supervisor.listAttendances')
@endsection

@section('js-index')
<script>
    fetchAll();
    function fetchAll(){
        $.ajax({
            url: '{{route('supervisor.fetchAll')}}',
            method: 'get',
            success: function (res){
                $('#Get_all_data').html(res);
                $("#scheduleTable").DataTable({
                    order: [0, 'desc']
                })
            }
        })
    }

    function startExam(id){
        Swal.fire({
            title: 'Xác nhận bắt đầu bài thi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{route('supervisor.startExam')}}',
                    method: 'post',
                    data:{
                        schedule_id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (res){
                        console.log(res)
                        Swal.fire(
                            'Đã Bắt Đầu!',
                            'Bài thi đã được bắt đầu',
                            'success',
                        )
                        fetchAll();
                    }
                })
            }
        })
    }

    function finishExam(id){
        Swal.fire({
            title: 'Xác nhận kết thúc bài thi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{route('supervisor.finishExam')}}',
                    method: 'post',
                    data:{
                        schedule_id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (res){
                        console.log(res)
                        Swal.fire(
                            'Đã Kết Thúc!',
                            'Bài thi đã được kết thúc',
                            'success',
                        )
                        fetchAll();
                    }
                })
            }
        })
    }
</script>



@endsection

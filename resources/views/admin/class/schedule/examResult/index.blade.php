<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Giám thị</title>
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <br>
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Lịch thi: {{$scheduleById->title}}
                                        <br><br>Kết quả thi</h5>
                                    <input type="hidden" value="{{$scheduleById->id}}" id="schedule_id">
                                </div>
                                <div class="col" align="right">
                                        <a href="{{route('resultExams.generatePDF', ['schedule_id' => $scheduleById->id])}}"
                                           target="_blank"
                                           class="btn btn-info">
                                            Tải file PDF
                                        </a>
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

@endsection

@section('js-index')
<script>
    fetchAll();
    function fetchAll(){
        var schedule_id = $('#schedule_id').val()
        $.ajax({
            url: '{{route('examResults.fetchAll')}}',
            method: 'get',
            data:{
                schedule_id:schedule_id
            },
            success: function (res){
                $('#Get_all_data').html(res);
                $("#tableExamResult").DataTable({
                    order: [0, 'desc']
                })
            }
        })
    }

    function deleteItem(id){
        Swal.fire({
            title: 'Bạn muốn giám thị này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xoá'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('lecturersOfSchedule.delete') }}',
                    method: 'post',
                    data:{
                        id:id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (res){
                        console.log(res)
                        if(res.status==200){
                            Swal.fire(
                                'Đã Xoá!',
                                'Xoá dữ liệu thành công!',
                                'success'
                            )
                            fetchAll()
                        }else if(res.status == 500){
                            Swal.fire(
                                'Thất Bại!',
                                'Có lỗi xảy ra!',
                                'error'
                            )
                            console.log(res)
                        }
                    }
                })
            }
        })
    }
</script>
@endsection

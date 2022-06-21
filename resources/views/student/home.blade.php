<!-- Stored in resources/views/child.blade.php -->

@extends('student.layouts.student')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('student.partials.content-header', ['name'=> 'Trang Chủ', 'key'=>''])


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                STUDENT HOME PAGE
            </div>
            <br>
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-header">
                        Danh Sách Bài Thi
                    </div>
                    <div class="card-body" id="Get_all_data">
                        <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('student.examInformation')
</div>
@endsection

@section('js')

<script>

    fetchAll();
    function fetchAll(){
    $.ajax({
        url: '{{route('homeStudents.fetchAll')}}',
        method: 'get',
        success: function (res){
            $('#Get_all_data').html(res);
            $("table").DataTable({
                order: [0, 'desc']
            })
            }
        })
    }

    function checkin(id){
        Swal.fire({
            title: 'Điểm danh bài thi này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Điểm danh'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{route('homeStudents.checkin')}}',
                    method: 'post',
                    data:{
                        schedule_id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (res){
                        console.log(res)
                        Swal.fire(
                            'Đã Điểm Danh!',
                            'Bạn đã điểm danh cho bài thi này',
                            'success',
                        )
                        fetchAll();
                        }
                })
            }
        })
    }



</script>


<script>
    function getDetailInfoExam(id){
        $.ajax({
            url: '{{route('homeStudents.examInfo')}}',
            method: 'get',
            data: {
                schedule_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                console.log(res)

                $('#codeExam').val(res['codeExam'].codeExam)
                $('#title').val(res['exam'].title)
                $('#totalQuestion').val(res['exam'].total_question)
                $('#semester').val(res['exam'].semester)
                $('#maxPoint').val(res['exam'].maxPoint)

            var url = "{{route('homeStudents.detailExam', '')}}"+"/"+res.attendances.id;
                $("#btn_start").html(`<a href="${url}" class="btn btn-primary">Làm Bài</a>`)
            }
        })
    }
</script>

@endsection

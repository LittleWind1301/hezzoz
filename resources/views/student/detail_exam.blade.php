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
                <div >
                    <h5>Làm Bài Thi</h5>
                    <input type="hidden" value="{{$codeExamId}}" id="codeExamId">
                </div>
                <br>

                <form  class="row" id="submitAnswer" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$attendance_id}}" name="attendance_id">
                    <div  id="Get_all_data" class="col-md-8">

                    </div>
                    <div class="col-md-4" align="center">
                        <h4>Thời gian đóng bài thi</h4>
                        <h3 class="text-danger text-bold">{{$timeCloseExam}}</h3>
                        <br>
                        <h3 class="text-danger text-bold" id="countdown"></h3>
                        <input type="hidden" value="{{$timeCloseExam}}" id="timeFinish">
                    </div>

                    <button class="btn btn-primary col-md-8" type="submit">Nộp bài</button>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('js')
<script>
    fetchAll();
    function fetchAll(){
        var codeExamId = $('#codeExamId').val()
        $.ajax({
            url: '{{route('homeStudents.fetchDetailExam')}}',
            method: 'get',
            data:{
                codeExamId:codeExamId
            },
            success: function (res){
                $('#Get_all_data').html(res);
            }
        })
    }

    //add new class jax
    $("#submitAnswer").submit(function (e){
        e.preventDefault();

        Swal.fire({
            title: 'Xác nhận nộp bài?',
            text: "Bạn sẽ không thể nộp lại đáp án nữa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK!'
        }).then((result) => {
            if (result.isConfirmed) {
                const fd= new FormData(this);

                $.ajax({
                    url: '{{ route('homeStudents.postAnswer') }}',
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (res){
                        console.log(res)
                        if(res.status === 200){
                            Swal.fire(
                                'Đã Nộp!',
                                res.messages,
                                'success'
                            ).then((result) => {
                                if (result.isConfirmed) {
                                    var url = "{{route('homeStudents.viewResult', '')}}"+"/"+res.schedule_id
                                    window.location.href = url
                                }
                            })

                        }else if(res.status === 0){
                            Swal.fire(
                                'Thất bại',
                                res.messages,
                                'error'
                            )
                        }else if(res.status === 500){
                            console.log(res)
                            Swal.fire(
                                'Thất bại',
                                'Có lỗi xảy ra',
                                'error'
                            )
                        }
                    }
                })

            }
        })



    })
</script>
<script>
    autoCloseExam()
    function autoCloseExam(){

        let timeFinish = $('#timeFinish').val()
        let today = new Date();
        let arrayTime = timeFinish.split(/[:/\s]/)
        let closeTime = arrayTime[4]*24*60*60 +  arrayTime[0]*60*60 + arrayTime[1]*60 + arrayTime[2]*1
        let currentTime =  today.getDate()*24*60*60 + today.getHours()*60*60 + today.getMinutes()*60 + today.getSeconds()
        let remainingTime = closeTime - currentTime;

        console.log(remainingTime)
        let timerId = setInterval(closeExam, remainingTime*1000)

        function closeExam(){
            alert('Đã Hết Giờ')
            clearInterval(timerId)
            Swal.fire({
                icon: 'info',
                text: 'Bài thi sẽ được nộp tự động!'
            }).then((result)=>{
                if(result.isConfirmed){

                    const form = document.getElementById('submitAnswer');
                    const fd= new FormData(form);

                    $.ajax({
                        url: '{{ route('homeStudents.postAnswer') }}',
                        method: 'post',
                        data: fd,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (res){
                            if(res.status === 200){
                                let url = "{{route('homeStudents.viewResult', '')}}"+"/"+res.schedule_id
                                window.location.href = url
                            }else if(res.status === 0){
                                Swal.fire(
                                    'Thất bại',
                                    res.messages,
                                    'error'
                                )
                            }
                        }
                    })
                }
            })
        }
    }

</script>
@endsection

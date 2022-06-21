<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
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
                                    <h5>Kết quả thi</h5>
                                    <br>Mã SV : {{$student->student_id}}
                                    <br><br>Họ tên : {{$student->student_name}}
                                </div>
                                <div class="col">
                                    Bài thi {{$exam->exam_id}} - {{$exam->title}}
                                    <br><br>Điểm số : <p class="text-bold" style="color: red">{{$attendance->mark}}</p>
                                </div>

                            </div>
                        </h5>
                        <div class="card-body" id="Get_all_data">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Câu hỏi</th>
                                        <th>Lựa chọn</th>
                                        <th>Đáp án của sinh viên</th>
                                        <th>Đáp án đúng</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($codeExamData as $item)
                                        <tr>
                                            <td>Câu hỏi số {{$item['questionNumber']}} : {!! $item['questionTitle'] !!}</td>
                                            <td>
                                                @foreach($item['option'] as $option)
                                                    <div class="row">
                                                        <div class="col-md-3">Đáp án số {{$option['optionNumber']}} :</div>
                                                        <div class="col-md-9"> {!! $option['optionTitle'] !!} </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($item['dataAnswer'] as $answer)
                                                    <div class="row">
                                                        <div class="col-md-3">Đáp án số {{$answer['optionNumber']}} :</div>
                                                        <div class="col-md-9"> {!! $answer['optionTitle'] !!} </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($item['correctAnswer'] as $correct)
                                                    <div class="row">
                                                        <div class="col-md-3">Đáp án số {{$correct['optionNumber']}} :</div>
                                                        <div class="col-md-9"> {!! $correct['optionTitle'] !!} </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($item['checkCorrectFlag'] == true)
                                                    <i class="fa fa-check-circle fa-2x text-success"></i>
                                                @elseif($item['checkCorrectFlag'] == false)
                                                    <i class="fa fa-times-circle fa-2x text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-index')

<script>
    $(document).ready( function () {
        $('table').DataTable();
    } );
</script>
@endsection

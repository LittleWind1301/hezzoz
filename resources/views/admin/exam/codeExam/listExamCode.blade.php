<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
<title>Mã Đề Thi</title>
@endsection

@section('content')

<div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Mã đề', 'key'=>''])
    <div class="content">
        <div class="container-fluid">
            <a href="{{ URL::previous() }}" class="btn btn-info">Trở về</a><br><br>
            <div class="row">
                <div class="card col-md-12">
                    <h5 class="card-header">
                        <div class="row">
                            <div class="col">Các mã đề của bài thi: </div>
                            <div class="col" align="right">

                            </div>
                        </div>
                    </h5>
                    <div class="card-body" >
                        <table class="table-striped table table-hover table-valign-middle" style="width: 20%">
                            @foreach($listCodeExam as $item)
                                <tr>
                                    <td><h4>mã đề {{$item->codeExam}}</h4></td>
                                    <td><a href="javascript:;" onclick="getDetailCodeExam({{$item->id}})" class="btn btn-primary">Xem </a></td>

                                </tr>
                            @endforeach

                        </table>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-8" id="getDetail"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js-index')

<script>

    function getDetailCodeExam(code_exam){
        $.ajax({
            url:'{{route('exams.getDetailCodeExam')}}',
            method: 'get',
            data:{
                code_exam:code_exam,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                $('#getDetail').html(res)
                $("#myTable").DataTable({
                    order: [0, 'desc']
                })
            }
        })
    }


</script>
@endsection

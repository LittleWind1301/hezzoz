<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
<title>Đề Thi</title>
@endsection

@section('content')

<div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Đề Thi', 'key'=>'Danh Sách'])
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card col-md-12">
                    <h5 class="card-header">
                        <div class="row">
                            <div class="col">Học phần {{$subjectById->subject_name}} \ Bài Thi</div>
                            <div class="col" align="right">

                                <a href="{{route('exams.create', ['subject_id'=>$subjectById->id])}}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </h5>
                    <div class="card-body" id="myTable">
                        <table class="table table-hover table-striped text-center">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Mã bài thi</th>
                                    <th scope="col">Tiêu Đề</th>
                                    <th>Học kì</th>
                                    <th>Điểm tối đa</th>
                                    <th scope="col">Số Câu Hỏi</th>
                                    <th scope="col">Người Tạo</th>
                                    <th scope="col">Các mã đề</th>
                                    <th>Ghi chú</th>
                                    <th scope="col">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exams as $item)
                                    <tr id="{{$item->id}}">
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->exam_id}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->semester}}</td>
                                        <td>{{$item->maxPoint}}</td>
                                        <td>{{$item->total_question}}</td>
                                        <td>{{$item->creator}}</td>
                                        <td>
                                            <a href="{{route('exams.viewCodeExam',['exam_id'=>$item->id])}}"  class="btn btn-success btn-sm">
                                                <i class="fa fa-folder"></i>
                                            </a>
                                        </td>
                                        <td>{{$item->description}}</td>
                                        <td>
                                            <a href="{{route('exams.edit',['id'=>$item->id])}}"  class="text-success mx-1">
                                                <i class="bi-pencil-square h4"></i>
                                            </a>
                                            <a href="javascript:;" onclick="deleteItem({{$item->id}})" class="text-danger mx-1 deleteIcon">
                                                <i class="bi-trash h4"></i>
                                            </a>
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

@if (Session::has('success'))
    <script>
        Swal.fire(
            'Thành công!',
            '{!! Session::get("success") !!}',
            'success'
        )
    </script>
@endif

<script>

    $(document).ready( function () {
        $('table').DataTable();
    } );

    function deleteItem(id){
    Swal.fire({
        title: 'Bạn muốn xoá đề thi này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Xoá'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route('exams.delete') }}',
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
                        document.getElementById(id).remove();
                    }
                }
            })
        }
    })
    }

</script>
@endsection

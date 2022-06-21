<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Nhóm câu hỏi</title>
@endsection

@section('content')

    <div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Nhóm câu hỏi', 'key'=>'Quản Lí Nhóm Câu Hỏi'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Học Phần: {{$subjectById->subject_code}} - {{$subjectById->subject_name}}</div>
                                <input type="hidden" value="{{$subjectById->id}}" id="subject_id">
                                <div class="col" align="right">
                                    <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    @include('admin.question_store.group_question.add')
                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="Show_all_data">
                            <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                        </div>
                    </div>
                </div>
                <a href="{{ URL::previous() }}" class="btn btn-primary">Trở về</a>
            </div>
        </div>
    </div>
    @include('admin.question_store.group_question.edit')

@endsection
@section('js-index')
    <script>
        fetchAll();
        function fetchAll(){
            var subject_id = $('#subject_id').val()
            $.ajax({
                url: '{{route('groupQuestions.fetchAll')}}',
                method: 'get',
                data:{
                    subject_id:subject_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (res){
                    $('#Show_all_data').html(res);
                    $("table").DataTable({
                        order: [0, 'desc']
                    })
                }
            })
        }

        function deleteItem(id){
            Swal.fire({
                title: 'Bạn muốn xoá bộ môn này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('groupQuestions.delete') }}',
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
@endsection

<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
<title>Ngân Hàng Câu Hỏi</title>
@endsection

@section('content')

<div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Câu Hỏi', 'key'=>'Danh Sách'])
    <div class="content">
        <div class="container-fluid">
            <a href="{{ URL::previous() }}" class="btn btn-info">Trở về</a><br><br>
            <div class="row">
                <div class="card col-md-12">
                    <h5 class="card-header">
                        <div class="row">
                            <div class="col">{{$groupQuestion->name}} \ Danh Sách Câu Hỏi</div>
                            <input type="hidden" value="{{$groupQuestion->id}}" id="groupQuestId">
                            <div class="col" align="right">

                                <a href="{{route('questions.create', ['groupQuestionId'=>$groupQuestion->id])}}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#importModal">
                                    Import file Excel
                                </button>
                                @include('admin.question_store.import')
                                @include('admin.question_store.add')
                            </div>
                        </div>
                    </h5>
                    <div class="card-body" id="Show_all_data">
                        <h1 class="text-center text-secondary my-5">Đang tải...</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js-index')

    @if (Session::has('updateSuccess'))
        <script>
            Swal.fire(
                'Đã Cập Nhật!',
                'Dữ liệu đã cập nhật thành công!',
                'success'
            )
        </script>
    @endif

    @if (Session::has('storeSuccess'))
        <script>
            Swal.fire(
                'Đã Thêm!',
                'Thêm câu hỏi thành công!',
                'success'
            )
        </script>
    @endif

    <script>
        fetchAll();
        function fetchAll(){
            var groupQuestId = $('#groupQuestId').val()
            $.ajax({
                url: '{{route('questions.fetchAll')}}',
                method: 'get',
                data:{
                    groupQuestId:groupQuestId,
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
                title: 'Bạn muốn xoá câu hỏi này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('questions.delete') }}',
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
                                fetchAll()
                            }

                        }
                    })
                }
            })
        }
    </script>

    <script>
        //add new class jax
        $("#add_form").submit(function (e){
            e.preventDefault();
            const fd= new FormData(this);
            $("#add_btn").text('Đang Thêm...');
            $.ajax({
                url: '{{ route('questions.storeImport') }}',
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
                            'Import file thành công',
                            'success')
                        fetchAll()
                        $("#add_btn").text('Thêm');
                        $("#add_form")[0].reset();
                        $("#close_modal_add").click();

                    }else if(res.status === 500){
                        Swal.fire(
                            'Thất bại',
                            res.messages,
                            'error')
                    }else if(res.status === 0){
                        Swal.fire(
                            'Thất bại',
                            'Có lỗi xảy ra!',
                            'error')
                    }

                },error:function (error){
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

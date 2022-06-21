<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Lớp học phần</title>
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
                                <h4>Học Phần : {{$subject->subject_name}} \ Danh Sách Lớp Học Phần</h4>
                                <input type="hidden" value="{{$subject->id}}" id="subject_id">
                            </div>
                            <div class="col" align="right">

                                <button class="btn btn-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#addClassModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                                @include('admin.class.add')
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
@include('admin.class.edit')

@endsection

@section('js-index')
<script>
    //fetch all class
    fetchAll();
    function fetchAll(){
        var subject_id = $('#subject_id').val()
        $.ajax({
            url: '{{route('classes.fetchAll')}}',
            method: 'get',
            data:{
                subject_id:subject_id
            },
            success: function (res){
                $('#Get_all_data').html(res);
                $("table").DataTable({
                    order: [0, 'desc']
                })
            }
        })
    }

    function deleteItem(id){
        Swal.fire({
            title: 'Bạn muốn xoá lớp học phần này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xoá'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('classes.delete') }}',
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
@endsection



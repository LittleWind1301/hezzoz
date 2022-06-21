<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Quản lí học viên</title>
@endsection

@section('content')

    <div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=>'Sinh Viên', 'key'=>'Danh Sách'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Lớp học phần: {{$classById->class_code}} - {{$classById->class_name}}
                                    <br><br> \ Danh sách sinh viên</div>
                                <input type="hidden" id="class_id" value="{{$classById->id}}">
                                <div class="col" align="right">

                                    <a href="#" id="{{$classById->id}}" class="text-success mx-1 createIcon" data-toggle="modal" data-target="#addModal">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    @include('admin.student_class.add')
                                </div>
                            </div>
                        </h5>
                        <div class="card-body" id="showAllData">
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

    fetchAll()
    function fetchAll(){

        var class_id = $('#class_id').val()

        $.ajax({
            url: '{{route('studentOfClasses.fetchAll')}}',
            method: 'get',
            data:{
              class_id:class_id
            },
            success:function (res){
                $("#showAllData").html(res);
                $("#tableAllStudent").DataTable({
                    order: [0, 'desc']
                })
            }
        })
    }

    function deleteItem(id){
        Swal.fire({
            title: 'Bạn muốn xoá nó?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xoá'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('student_classes.delete') }}',
                    method: 'post',
                    data:{
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (res){
                        if(res.status==200){
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

@extends('admin.layouts.admin')

@section('content')

<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card col-md-12">
                    <h5 class="card-header">
                        <div class="row">
                            <div class="col">Thêm Đề Thi</div>
                        </div>
                    </h5>
                    <div class="card-body">
                        <form action="{{route('exams.store')}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class=" shadow-lg p-3 mb-5 bg-white rounded">
                                        <h5>Thông tin bài thi</h5>
                                        <br>

                                        <div class="form-group">
                                            <label for=""><h5>Mã bài thi</h5></label>
                                            <input type="text" class="form-control" id="" placeholder="" name="exam_id" required>
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for=""><h5>Tiêu đề</h5></label>
                                            <input type="text" class="form-control" id="" placeholder="" name="title" required>
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Học kì:</label>
                                            <select type="text" class="form-control"name="semester" required>
                                                <option value="">---Chọn học kì---</option>
                                                <option value="2_2026_2027">2_2026_2027</option>
                                                <option value="1_2026_2027">1_2026_2027</option>
                                                <option value="2_2025_2026">2_2025_2026</option>
                                                <option value="1_2025_2026">1_2025_2026</option>
                                                <option value="2_2024_2025">2_2024_2025</option>
                                                <option value="1_2024_2025">1_2024_2025</option>
                                                <option value="2_2023_2024">2_2023_2024</option>
                                                <option value="1_2023_2024">1_2023_2024</option>
                                                <option value="2_2022_2023">2_2022_2023</option>
                                                <option value="1_2022_2023">1_2022_2023</option>
                                                <option value="2_2021_2022">2_2021_2022</option>
                                                <option value="1_2021_2022">1_2021_2022</option>
                                                <option value="2_2020_2021">2_2020_2021</option>
                                                <option value="1_2020_2021">1_2020_2021</option>
                                            </select>
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for=""><h5>Điểm tối đa</h5></label>
                                            <input type="number" class="form-control" id="" placeholder="" name="maxPoint" required min="0">
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for=""><h5>Số lượng mã đề tối đa</h5></label>
                                            <input type="number" class="form-control" id="" placeholder="" name="numberOfCodeExam" required min="1">
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for=""><h5>Ghi chú</h5></label>
                                            <textarea name="description" id="" class="form-control" rows="5" required></textarea>
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" ><h5>Học phần</h5></label>
                                            <input disabled type="text" class="form-control " value="{{$subjectById->subject_name}}" required>
                                            <input  type="hidden" name="subject_id" value="{{$subjectById->id}}">
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                        <div id="group_ques" class="form-group shadow p-3 mb-5 bg-white rounded">

                                            @foreach($listGrQuestion as $item)

                                            <div class="form-group">
                                                <div class="row">
                                                    <input type="hidden" name="grQuesId[]" value="{{$item->id}}">
                                                    <div class="col-md-8">
                                                        <label for="">
                                                            <h5> Số câu hỏi {{$item->name}}: </h5>
                                                            <h6>(hiện có:{{$item->numQuestion}} câu hỏi)</h6>
                                                        </label>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="numQues[]" class="form-control" value="" required min="0" max="{{$item->numQuestion}}">
                                                        <div class="valid-feedback">Valid.</div>
                                                        <div class="invalid-feedback">Please fill out this field.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            <button class="btn btn-success" id="randomQuestion">Sinh Câu Hỏi Ngẫu Nhiên</button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tạo bài thi</button>
                                </div>

                                <div class="col-md-8">
                                    <div class="shadow-lg p-3 mb-5 bg-white rounded">
                                        <h5>Danh Sách Câu Hỏi</h5>
                                        <div class="form-group" id="listQuestion">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js-index')

@if (Session::has('error'))
    <script>
        Swal.fire(
            'Thất Bại!',
            'Có lỗi xảy ra, vui lòng thử lại',
            'error'
        )
        console.log("{!! Session::get('error') !!} }")
    </script>
@endif

<script>

    $(document).ready( function () {
        $('table').DataTable();
    } );

    $(document).on('click', '#randomQuestion', function(e) {
        e.preventDefault()

        const groupQues = []
        const numQues = []

        $('input[name="grQuesId[]"]').each(function() {
            groupQues.push( $(this).val())
        })

        $('input[name="numQues[]"]').each(function() {
            numQues.push( $(this).val())
        })
        $.ajax({
            url:'{{route('exams.randomQuestion')}}',
            method:'get',
            data: {
                groupQues:groupQues,
                numQues:numQues,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                if(res[0] === false){
                    Swal.fire(
                        'Thất bại!',
                        'Số lượng câu hỏi không hợp lệ!',
                        'error'
                    )
                }

                if(res.status === 0){
                    Swal.fire(
                        'Thất bại!',
                        res.messages,
                        'error'
                    )
                }
                else if(res.status === 500){
                    Swal.fire(
                        'Thất bại!',
                        'Có lỗi xảy ra!',
                        'error'
                    )
                }else {
                    $('#listQuestion').html(res);
                }
            }
        })
    })

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
                document.getElementById(id).remove();
            }
        })
    }
</script>

<script>
    // Disable form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Get the forms we want to add validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
@endsection

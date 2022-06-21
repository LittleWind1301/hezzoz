<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Đề Thi</title>
@endsection

@section('content')

    <div class="content-wrapper">
        @include('admin.partials.content-header', ['name'=>'Đề Thi', 'key'=>'Sửa'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Cập Nhật Bài Thi</div>
                            </div>
                        </h5>
                        <div class="card-body">
                            <form action="{{route('exams.update', ['id'=>$examById->id])}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 shadow-lg p-3 mb-5 bg-white rounded">
                                        <h5>Thông tin bài thi</h5>
                                        <br>

                                        <div class="form-group">
                                            <label for=""><h5>Mã bài thi</h5></label>
                                            <input type="text" class="form-control" id="" value="{{$examById->exam_id}}" placeholder="" name="exam_id" required>
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for=""><h5>Tiêu đề</h5></label>
                                            <input type="text" class="form-control" id="" placeholder="" name="title" value="{{$examById->title}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Học kì:</label>
                                            <select type="text" class="form-control" name="semester" required>
                                                <option value="">---Chọn học kì---</option>
                                                <option value="2_2026_2027" {{$examById->semester=='2_2026_2027' ? 'selected="selected"' : ''}}>2_2026_2027</option>
                                                <option value="1_2026_2027" {{$examById->semester=='1_2026_2027' ? 'selected="selected"' : ''}}>1_2026_2027</option>
                                                <option value="2_2025_2026" {{$examById->semester=='2_2025_2026' ? 'selected="selected"' : ''}}>2_2025_2026</option>
                                                <option value="1_2025_2026" {{$examById->semester=='1_2025_2026' ? 'selected="selected"' : ''}}>1_2025_2026</option>
                                                <option value="2_2024_2025" {{$examById->semester=='2_2024_2025' ? 'selected="selected"' : ''}}>2_2024_2025</option>
                                                <option value="1_2024_2025" {{$examById->semester=='1_2024_2025' ? 'selected="selected"' : ''}}>1_2024_2025</option>
                                                <option value="2_2023_2024" {{$examById->semester=='2_2023_2024' ? 'selected="selected"' : ''}}>2_2023_2024</option>
                                                <option value="1_2023_2024" {{$examById->semester=='1_2023_2024' ? 'selected="selected"' : ''}}>1_2023_2024</option>
                                                <option value="2_2022_2023" {{$examById->semester=='2_2022_2023' ? 'selected="selected"' : ''}}>2_2022_2023</option>
                                                <option value="1_2022_2023" {{$examById->semester=='1_2022_2023' ? 'selected="selected"' : ''}}>1_2022_2023</option>
                                                <option value="2_2021_2022" {{$examById->semester=='2_2021_2022' ? 'selected="selected"' : ''}}>2_2021_2022</option>
                                                <option value="1_2021_2022" {{$examById->semester=='1_2021_2022' ? 'selected="selected"' : ''}}>1_2021_2022</option>
                                                <option value="2_2020_2021" {{$examById->semester=='2_2020_2021' ? 'selected="selected"' : ''}}>2_2020_2021</option>
                                                <option value="1_2020_2021" {{$examById->semester=='1_2020_2021' ? 'selected="selected"' : ''}}>1_2020_2021</option>
                                            </select>
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for=""><h5>Điểm tối đa</h5></label>
                                            <input type="number" class="form-control" id="" placeholder="" name="maxPoint" required min="1" value="{{$examById->maxPoint}}">
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for=""><h5>Số lượng mã đề tối đa</h5></label>
                                            <input type="number" class="form-control" id="" placeholder="" name="numberOfCodeExam" required min="1" value="{{$examById->numberOfCodeExam}}">
                                            <div class="valid-feedback">Valid.</div>
                                            <div class="invalid-feedback">Please fill out this field.</div>
                                        </div>
                                        <div class="form-group">
                                            <label for=""><h5>Mô Tả</h5></label>
                                            <textarea name="description" id="" class="form-control" rows="5">{{$examById->description}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for=""><h5>Môn Học</h5></label>
                                            <input disabled type="text" class="form-control" value="{{$subjectById->subject_name}}">
                                            <input  type="hidden" name="subject_id" value="{{$subjectById->id}}">
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
                                                            <input type="number" name="numQues[]" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <button class="btn btn-success" id="randomQuestion">Sinh Câu Hỏi Ngẫu Nhiên</button>
                                        </div>
                                    </div>
                                    <div class="col-md-8 shadow-lg p-3 mb-5 bg-white rounded">
                                        <h5>Danh Sách Câu Hỏi</h5>
                                        <div class="form-group" id="listQuestion">
                                            <table class="table table-bordered table-sm ">
                                                <thead>
                                                <tr>
                                                    <th>Mã</th>
                                                    <th>Nhóm Câu Hỏi</th>
                                                    <th>Nội Dung</th>
                                                    <th>Mức Độ</th>
                                                    <th>#</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($questions as $item)
                                                    <tr id="{{$item->id}}">
                                                        <td>
                                                            <input type=hidden name="listQuestId[]" value = "{{$item->id}}">
                                                            {{$item->id}}
                                                        </td>
                                                        <td>{{$item->groupQuestion->name}}</td>
                                                        <td>{!! $item->question_content !!}</td>
                                                        <td>{{$item->level}}</td>
                                                        <td>
                                                            <a href="javascript:;" id="" onclick="deleteItem({{$item->id}})" class="text-danger mx-1 deleteIcon">
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
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </form>
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
                $('#listQuestion').html(res);
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

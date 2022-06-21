<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Câu hỏi</title>
@endsection

@section('content')

    <div class="content-wrapper">
        @include('admin.partials.content-header', ['name'=>'Câu hỏi', 'key'=>'Chỉnh sửa'])
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Cập Nhật Câu Hỏi</div>
                            </div>
                        </h5>
                        <div class="card-body">
                            <form action="{{route('questions.update', ['id'=>$questionById->id])}}" method="post" enctype="multipart/form-data">
                                @csrf

                                @if($errors->any()>0)
                                    <div class="alert alert-danger text-center">Vui lòng nhập đầy đủ dữ liệu</div>
                                @endif

                                <div class="form-group row">
                                    <label for="recipient-name" class="col-form-label col-md-2">Nhóm câu hỏi</label>
                                    <input type="text" disabled value="{{$groupQuestion->name}}" class="form-control col-md-2">
                                    <input type="hidden" value="{{$questionById->group_question_id}}" id="groupQuestionId" name="group_question_id">
                                </div>

                                <div class="form-group row">
                                    <label for="recipient-name" class="col-form-label col-md-2">Mức độ
                                        <br>
                                        @error('level')
                                        <span style="color: red">{{$message}}</span>
                                        @enderror
                                    </label>

                                    <select name="level" class="form-control col-md-2" >
                                        <option value="">Chọn mức độ câu hỏi</option>
                                        <option {{$questionById->level=='Đánh Giá'?'selected':''}} value="Đánh Giá">
                                            Đánh Giá</option>
                                        <option {{$questionById->level=='Tổng Hợp'?'selected':''}} value="Tổng Hợp">
                                            Tổng Hợp</option>
                                        <option {{$questionById->level=='Phân Tích'?'selected':''}} value="Phân Tích">
                                            Phân Tích</option>
                                        <option {{$questionById->level=='Vận Dụng'?'selected':''}} value="Vận Dụng">
                                            Vận Dụng</option>
                                        <option {{$questionById->level=='Hiểu'?'selected':''}} value="Hiểu">
                                            Hiểu</option>
                                        <option {{$questionById->level=='Biết'?'selected':''}} value="Biết">
                                            Biết</option>
                                    </select>
                                </div>

                                <div class="form-group row">
                                    <label for="recipient-name" class="col-form-label col-md-2">Nội dung câu hỏi
                                        <br>
                                        @error('question_content')
                                        <span style="color: red">{{$message}}</span>
                                        @enderror
                                    </label>
                                    <div class="col-md-8">
                                        <textarea name="question_content" class="form-control add-question-editor col-md-8">{{$questionById->question_content}}</textarea>

                                    </div>

                                </div>

                                <div id="option_answer">
                                    @foreach($questionById->option as $option)

                                    <div class="form-group row" id="row_{{$option->optionNumber}}">
                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label">Đán Án {{$option->optionNumber}}</label>
                                            <div class="form-check">
                                                <input name="correct[]" class="form-check-input" type="checkbox" value="{{$option->optionNumber}}"   @if($option->isCorrect) checked @endif>
                                                <label class="form-check-label" for="defaultCheck1">
                                                    Đáp án đúng
                                                </label>
                                                <br>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <textarea name="option_title[]" class="form-control add-question-editor">{{$option->optionTitle}}</textarea>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row" >
                                    <div class="col-md-2"></div>

                                    <div id="btnAddOption" class="col-md-1">
                                        <i class="fa fa-plus fa-lg text-success" ></i>
                                    </div>

                                    <div id="btnDeleteOption" class="col-md-1">
                                        <i class="fa fa-minus fa-lg text-danger" ></i>
                                    </div>

                                    <input type="hidden" value="{{count($questionById->option)}}" id="stt">

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
@section('js-edit')

    @if (\Session::has('fails'))
        <script>
            console.log('hehe')
            Swal.fire(
                'Đã Xoá!',
                'Xoá dữ liệu thành công!',
                'success'
            )
        </script>
    @endif

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>


    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js'></script>

    <script src="https://cdn.tiny.cloud/1/zstgk90zad701ypjlvwsk5kudvvd7iqyxakqwh7kbxongq9r/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <script>

        initializeIt('textarea.add-question-editor')

        function initializeIt(selector) {
            var editor_config = {
                path_absolute: "/",
                selector: selector,
                relative_urls: false,
                height: "200px",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table directionality",
                    "emoticons template paste textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                file_picker_callback: function (callback, value, meta) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                    if (meta.filetype == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.openUrl({
                        url: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no",
                        onMessage: (api, message) => {
                            callback(message.content);
                        }
                    });
                }
            };

            tinymce.init(editor_config);
        }

        $("#btnAddOption").click(function(){
            var stt = $('#stt').val()
            console.log(stt)
            stt++
            $("#option_answer").append(
                '<div class="form-group row" id="row_'+stt+'">' +
                '<div class="col-md-2">'+
                '<label for="recipient-name" class="col-form-label ">Đán Án '+stt+'</label>'+
                '<div class="form-check">'+
                '<input name="correct[]" class="form-check-input" type="checkbox" value="'+stt+'" id="defaultCheck1">'+
                '<label class="form-check-label" for="defaultCheck1">Đáp án đúng</label>'+
                '</div>'+
                '</div>'+
                '<div class="col-md-8"><textarea name="option_title[]" class="form-control add-question-editor" ></textarea></div>'+
                '</div>');
            initializeIt('textarea.add-question-editor')
            $('#stt').val(stt)
        });

        $("#btnDeleteOption").click(function(e){

            var stt = $('#stt').val()

            if(stt >2 ){
                $('#row_'+stt).remove()
                stt = parseInt(stt)-1
                $('#stt').val(stt)
            }

        });

    </script>
@endsection

<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title></title>
@endsection

@section('content')

    <div class="content-wrapper">

        <div class="content">
            <div class="container-fluid">
                <a href="{{ URL::previous() }}" class="btn btn-info">Trở về</a><br><br>
                <div class="row">
                    <div class="card col-md-12">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col">Thêm Câu Hỏi</div>
                            </div>
                        </h5>
                        <div class="card-body">
                            <form action="{{route('questions.store')}}" method="post" enctype="multipart/form-data">
                                @csrf

                                @if($errors->any()>0)
                                    <div class="alert alert-danger text-center">Vui lòng nhập đầy đủ dữ liệu</div>
                                @endif

                                <div class="form-group row">
                                    <label for="recipient-name" class="col-form-label col-md-2">Nhóm câu hỏi</label>
                                    <input type="text" disabled value="{{$groupQuestion->name}}" class="form-control col-md-2">
                                    <input type="hidden" value="{{$groupQuestion->id}}" id="groupQuestionId" name="group_question_id">
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
                                        <option value="Đánh Giá">Đánh Giá</option>
                                        <option value="Tổng Hợp">Tổng Hợp</option>
                                        <option value="Phân Tích">Phân Tích</option>
                                        <option value="Vận Dụng">Vận Dụng</option>
                                        <option value="Hiểu">Hiểu</option>
                                        <option value="Biết">Biết</option>
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
                                        <textarea  name="question_content" class=" form-control add-question-editor">{{old('question_content')}}</textarea>
                                    </div>
                                </div>
                                <br>

                                <div id="option_answer">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label ">Đán Án 1</label>
                                            <div class="form-check">
                                                <input name="correct[]" class="form-check-input" type="checkbox" value="1" id="">
                                                <label class="form-check-label" for="">
                                                    Đáp án đúng
                                                </label>
                                                <br>

                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea id="option_title.0" name="option_title[]" class="form-control add-question-editor" ></textarea>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label ">Đán Án 2</label>
                                            <div class="form-check">
                                                <input name="correct[]" class="form-check-input" type="checkbox" value="2" id="">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    Đáp án đúng
                                                </label>
                                                <br>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <textarea id="option_title.1" name="option_title[]" class="form-control add-question-editor" ></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="row" >

                                    <div class="col-md-2"></div>

                                    <div id="btnAddOption" class="col-md-1">
                                        <i class="fa fa-plus fa-lg text-success" ></i>

                                    </div>

                                    <div id="btnDeleteOption" class="col-md-1">
                                        <i class="fa fa-minus fa-lg text-danger" ></i>
                                    </div>
                                    <input type="hidden" value="" id="stt">

                                </div>

                                <br>
                                <button type="submit" class="btn btn-primary">Thêm</button>


                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('js-index')
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


        $("#btnAddOption").click(function(e){
            e.preventDefault()

            var stt = 3
            if($('#stt').val() == ''){

                $("#option_answer").append(
                    '<div class="form-group row" id="add_row_'+stt+'">' +
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

            }else {

                var stt = parseInt($('#stt').val()) + 1
                $("#option_answer").append(
                    '<div class="form-group row" id="add_row_'+stt+'">' +
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

            }


        });

        $("#btnDeleteOption").click(function(e){

            var stt = $('#stt').val()

            if(stt >2 ){
                $('#add_row_'+stt).remove()
                stt = parseInt(stt)-1
                $('#stt').val(stt)
            }


        });


    </script>
@endsection

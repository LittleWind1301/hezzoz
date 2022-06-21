<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Bộ Môn Mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('questions.store')}}" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="text-align: left">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label"><h5>Nội dung câu hỏi</h5></label>
                        <textarea name="question_content" class="form-control add-question-editor" rows="8"></textarea>
                    </div>

                    <br>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label"><h5>Đán Án 1</h5></label>
                        <textarea name="question_title" class="form-control add-question-editor" rows="8"></textarea>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label"><h5>Đán Án 2</h5></label>
                        <textarea name="question_title" class="form-control add-question-editor" rows="8"></textarea>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label"><h5>Đán Án 3</h5></label>
                        <textarea name="question_title" class="form-control add-question-editor" rows="8"></textarea>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label"><h5>Đán Án 4</h5></label>
                        <textarea name="question_title" class="form-control add-question-editor" rows="8"></textarea>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label"><h5>Mức độ</h5></label>
                        <select name="question_level" class="form-control" >
                            <option value="">Chọn mức độ câu hỏi</option>
                            <option value="Đánh Giá">Đánh Giá</option>
                            <option value="Tổng Hợp">Tổng Hợp</option>
                            <option value="Phân Tích">Phân Tích</option>
                            <option value="Vận Dụng">Vận Dụng</option>
                            <option value="Hiểu">Hiểu</option>
                            <option value="Biết">Biết</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label"><h5>Đáp án đúng</h5></label>
                        <select name="correct_answer" class="form-control" >
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label"><h5>Nhóm câu hỏi</h5></label>
                        <input type="text" disabled value="{{$groupQuestion->name}}" >
                        <input type="hidden" value="{{$groupQuestion->id}}" id="groupQuestionId" name="group_question_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('js-add')
<script src="https://cdn.tiny.cloud/1/zstgk90zad701ypjlvwsk5kudvvd7iqyxakqwh7kbxongq9r/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>

    var editor_config = {
        path_absolute : "/",
        selector: 'textarea.add-question-editor',
        relative_urls: false,
        height: "300px",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table directionality",
            "emoticons template paste textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
        file_picker_callback : function(callback, value, meta) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
            if (meta.filetype == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url : cmsURL,
                title : 'Filemanager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no",
                onMessage: (api, message) => {
                    callback(message.content);
                }
            });
        }
    };

    tinymce.init(editor_config);


</script>

@endsection




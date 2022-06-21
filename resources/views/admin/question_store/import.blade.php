<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Câu Hỏi Từ File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('questions.storeImport')}}" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="text-align: left">
                    <div class="form-control">
                        <input type="file" name="file" id="file">
                        <input type="hidden" value="{{$groupQuestion->id}}" id="groupQuestionId" name="group_question_id">
                        <br>
                        <span style="color: red" class="error file_error"></span>
                    </div>
                    <br><br>
                    <a href="{{url('/sample/addQuestionForm.xlsx')}}">Tải bản mẫu</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_btn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>








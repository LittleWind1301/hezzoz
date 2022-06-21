<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm sinh viên từ file excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" id="importStudent"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body" align="left">
                    <div class="form-group">
                        <input class="form-control" type="file" name="file" id="file">
                        <input type="hidden" value="" id="groupQuestionId"  name="group_question_id">
                        <span style="color: red" class="error file_error"></span>
                    </div>
                    <input name="faculty_id" type="hidden" value="{{$facultyById->id}}">
                    <br><br>
                    <a href="{{url('/sample/addStudentForm.xlsx')}}">Tải bản mẫu</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="add_student_btn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>





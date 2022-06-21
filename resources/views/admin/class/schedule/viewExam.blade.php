<div class="modal fade" id="viewExamModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Xem thông tin bài thi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_exam_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body" >
                    <div id="detailExam"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_view_exam_modal" data-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>


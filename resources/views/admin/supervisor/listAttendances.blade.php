<div class="modal fade" id="listAttendances" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Danh Sách SV Đã Điểm Danh</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="add_form"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div id="show_list_attendance">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_modal_add_faculty" data-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js-add')
<script>

    function getAttendancesList(id){
        $.ajax({
            url: '{{route('supervisor.getAttendancesList')}}',
            method: 'get',
            data: {
                schedule_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function (res){
                $('#show_list_attendance').html(res);
                $("#myTable").DataTable({
                    order: [0, 'desc']
                })
            }
        })
    }
</script>

@endsection





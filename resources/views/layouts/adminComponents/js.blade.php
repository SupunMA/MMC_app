<!-- jQuery -->
{{-- <script src={{ URL::asset('plugins/jquery/jquery.min.js'); }}></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Bootstrap 4 -->
{{-- <script src={{ URL::asset('plugins/bootstrap/js/bootstrap.bundle.min.js'); }}></script> --}}
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- AdminLTE App -->

{{-- <script src={{ URL::asset('dist/js/adminlte.min.js'); }}></script> --}}
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

<!-- Modal js -->
<script>
$('#myModal').on('shown.bs.modal', function () 
{
    $('#myInput').trigger('focus')
})
</script>

<!-- DataTables  & Plugins -->

<script src={{ URL::asset('plugins/datatables/jquery.dataTables.min.js'); }}></script>
<script src={{ URL::asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); }}></script>
<script src={{ URL::asset('plugins/datatables-responsive/js/dataTables.responsive.min.js'); }}></script>
<script src={{ URL::asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js'); }}></script>
<script src={{ URL::asset('plugins/datatables-buttons/js/dataTables.buttons.min.js'); }}></script>
<script src={{ URL::asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js'); }}></script>
<script src={{ URL::asset('plugins/jszip/jszip.min.js'); }}></script>
<script src={{ URL::asset('plugins/pdfmake/pdfmake.min.js'); }}></script>
<script src={{ URL::asset('plugins/pdfmake/vfs_fonts.js'); }}></script>
<script src={{ URL::asset('plugins/datatables-buttons/js/buttons.html5.min.js'); }}></script>
<script src={{ URL::asset('plugins/datatables-buttons/js/buttons.print.min.js'); }}></script>
<script src={{ URL::asset('plugins/datatables-buttons/js/buttons.colVis.min.js'); }}></script>


<!-- AdminLTE for demo purposes -->
<script src="{{ URL::asset('dist/js/demo.js'); }}"></script>
<!-- Page specific script -->
<script>
$(function () {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});

</script>
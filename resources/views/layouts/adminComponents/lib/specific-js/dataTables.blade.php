<!-- DataTables  & Plugins -->

<script src={{ URL::asset('adminPages/plugins/datatables/jquery.dataTables.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/datatables-responsive/js/dataTables.responsive.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/datatables-buttons/js/dataTables.buttons.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/datatables-buttons/js/buttons.bootstrap4.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/jszip/jszip.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/pdfmake/pdfmake.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/pdfmake/vfs_fonts.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/datatables-buttons/js/buttons.html5.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/datatables-buttons/js/buttons.print.min.js'); }}></script>
<script src={{ URL::asset('adminPages/plugins/datatables-buttons/js/buttons.colVis.min.js'); }}></script>


<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [[0, 'desc']],
            "buttons": ["excel", "pdf", "print","copy", "colvis"] //"csv","copy"
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        // $('#example2').DataTable({
        //     "paging": true,
        //     "lengthChange": false,
        //     "searching": false,
        //     "ordering": true,
        //     "info": true,
        //     "autoWidth": false,
        //     "responsive": true,
        // });
    });

</script>

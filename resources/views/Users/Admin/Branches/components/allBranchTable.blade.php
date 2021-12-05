<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">All Branches</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Branch ID</th>
                    <th>Branch Name</th>
                    <th>Address</th>
                    <th>Telephone</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($bdata as $bd)
                    <tr>
                        <td>{{$bd->branchID}}</td>
                        <td>{{$bd->branchName}}</td>
                        <td>{{$bd->branchAddress}}</td>
                        <td>{{$bd->branchTP}}</td>
                        <td>
                            <a class="btn bg-gradient-primary" href="{{$bd->branchLocation}}" target="_blank">
                                <i class="fas fa-map-marked-alt"></i>
                                check the map
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-warning" type="button" data-toggle="modal" data-target="#branchEditModal-{{$bd->branchID}}" >
                                <i class="far fa-edit"></i>
                            </a>
                            <a class="btn btn-danger" href="branch/delete/{{$bd->branchID}}" >
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>

                    @include('Users.Admin.Branches.components.updateBranch')
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <th>Branch ID</th>
                    <th>Branch Name</th>
                    <th>Address</th>
                    <th>Telephone</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->


@push('specificJs')
{{-- toastr msg --}}
<script>
    $('.toastrDefaultSuccess').click(function () {
        toastr.success('&#160; Done Successfully !.&#160;')
    });

</script>

{{-- toastr auto click --}}
<script type="text/javascript">
    $(document).ready(function () {
        $(".toastrDefaultSuccess").click();
    });

</script>
@endpush
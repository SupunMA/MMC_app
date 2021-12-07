<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">All Lands</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        @include('Users.Admin.messages.addMsg')
        
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Land ID</th>
                    <th>Owner's Name - NIC</th>
                    <th>Value</th>
                    <th>Land Address</th>
                    <th>Land Details</th>
                    <th>Land Map</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($landsAndClients as $data)
                <tr>
                    <td>{{$data->landID}}</td>
                    <td>{{$data->name}} - {{$data->NIC}}</td>
                    <td>{{$data->landValue}}</td>
                    <td>{{$data->landAddress}}</td>
                    <td>{{$data->landDetails}}</td>
                    <td>
                        <a class="btn bg-gradient-primary" href="{{$data->landMap}}" target="_blank">
                            <i class="fas fa-map-marked-alt"></i>
                            check the map
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-warning" type="button" data-toggle="modal" data-target="#landEditModal-{{$data->landID}}" >
                            <i class="far fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" type="button" data-toggle="modal" data-target="#landDeleteModal-{{$data->landID}}"  >
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>

                    {{-- update modal and delete modal --}}
                    @include('Users.Admin.Lands.components.updateLand')
                    @include('Users.Admin.Lands.components.deleteLand')

                @endforeach
                
            </tbody>
            <tfoot>
                <tr>
                    <th>Land ID</th>
                    <th>Owner's Name - NIC</th>
                    <th>Value</th>
                    <th>Land Address</th>
                    <th>Land Details</th>
                    <th>Land Map</th>
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

    $('.toastrDefaultError').click(function () {
        toastr.error("Could't Save the Data. Please try again")
    });

</script>

{{-- toastr auto click --}}
<script type="text/javascript">
    $(document).ready(function () {
        $(".toastrDefaultSuccess").click();
        $(".toastrDefaultError").click();
    });

</script>

@endpush
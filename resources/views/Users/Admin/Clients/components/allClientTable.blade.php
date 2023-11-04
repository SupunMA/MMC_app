<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">All Clients</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        @include('Users.Admin.messages.addMsg')



        <table id="example1" class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>NIC</th>
                    <th>File</th>
                    <th>Branch</th>
                    <th>Map/Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)

                    <tr>
                        <td>{{$client->id}}</td>
                        <td>{{$client->name}}</td>
                        <td>{{$client->address}}</td>
                        <td>{{$client->mobile}}</td>
                        <td>{{$client->NIC}}</td>
                        <td>{{$client->fileName}}</td>
                        <td>{{$client->branchName}}</td>
                        <td>
                            <a name="" id="" class="btn btn-secondary" target="_blank" href="{{$client->userMap}}" role="button"><i class="fas fa-street-view"></i></a>
                            <a name="" id="" class="btn btn-primary" target="_blank" href="https://drive.google.com/uc?export=view&id={{$client->photo}}" role="button"><i class="fas fa-portrait"></i></a>
                        </td>


                        <td>
                            <a class="btn btn-warning" type="button" data-toggle="modal" data-target="#ClientEditModal-{{$client->id}}" >
                                <i class="far fa-edit"></i>
                            </a>
                            <a class="btn btn-danger" type="button" data-toggle="modal" data-target="#clientDeleteModal-{{$client->id}}"  >
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- delete modal --}}
                    @include('Users.Admin.Clients.components.deleteClient')
                    {{-- update modal --}}
                    @include('Users.Admin.Clients.components.updateClient')


                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>NIC</th>
                    <th>File</th>
                    <th>Branch</th>
                    <th>Map/Photo</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->


@push('specificCss')
@include('layouts.adminComponents.lib.specific-style.dataTables')

@endpush

@push('specificJs')
{{-- toastr msg --}}

{{-- toastr auto click --}}
<script type="text/javascript">
    $(document).ready(function () {
        $(".toastrDefaultSuccess").click();
        $(".toastrDefaultError").click();
    });

</script>


<!-- DataTables  & Plugins -->

@include('layouts.adminComponents.lib.specific-js.dataTables')
@include('layouts.adminComponents.lib.specific-js.formInput')


@endpush

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
                    <th>Map Location</th>
                </tr>
            </thead>
            <tbody>
@foreach ($bdata as $bd)
                <tr>
                    <td>{{$bd->id}}</td>
                    <td>{{$bd->branchName}}</td>
                    <td>{{$bd->branchAddress}}</td>
                    <td>{{$bd->branchTP}}</td>
                    <td><a class="btn btn-info" href="{{$bd->branchLocation}}" target="_blank">View</a></td>
                </tr>
@endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Branch ID</th>
                    <th>Branch Name</th>
                    <th>Address</th>
                    <th>Telephone</th>
                    <th>Map Location</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
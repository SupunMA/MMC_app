<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">All Clients</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
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
                            <a name="" id="" class="btn btn-secondary" href="{{$client->userMap}}" role="button"><i class="fas fa-street-view"></i></a>
                            <a name="" id="" class="btn btn-primary" href="https://drive.google.com/uc?export=view&id={{$client->photo}}" role="button"><i class="fas fa-portrait"></i></a>
                        </td>
                        
                        
                        <td>
                            <a name="" id="" class="btn btn-danger" href="#" role="button">Delete</a>
                            <a name="" id="" class="btn btn-warning" href="#" role="button">Edit</a>
                        </td>
                    </tr>

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
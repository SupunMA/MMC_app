<div class="card card-danger">
    <div class="card-header">
        <h3 class="card-title">All Loans</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        @include('Users.Admin.messages.addMsg')

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Land ID - Owner - NIC</th>
                    <th>Loan Amount</th>
                    <th>Loan Rate</th>
                    <th>Penalty Fee Rate</th>
                    <th>Loan Date | Due Date</th>
                    
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($LoansWithLand as $data)
                    
                <tr>
                    <td>{{$data->loanID}}</td>
                    <td>{{$data->landID}} <br> {{$data->name}} <br> {{$data->NIC}}</td>
                    <td>{{$data->loanAmount}}</td>
                    <td>{{$data->loanRate}}%</td>
                    <td>{{$data->penaltyRate}}%</td>
                    <td>{{$data->loanDate}}</td>
                    
                    <td>{{$data->description}}</td>
                    <td>
                        <a class="btn btn-warning" type="button" data-toggle="modal" data-target="#loanEditModal-{{$data->loanID}}" >
                            <i class="far fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" type="button" data-toggle="modal" data-target="#loanDeleteModal-{{$data->loanID}}"  >
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>

                {{-- update modal and delete modal --}}
                @include('Users.Admin.Loans.components.updateLoan')
                @include('Users.Admin.Loans.components.deleteLoan')

                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Loan ID</th>
                    <th>Land ID - Owner - NIC</th>
                    <th>Loan Amount</th>
                    <th>Loan Rate</th>
                    <th>Penalty Fee Rate</th>
                    <th>Loan Date | Due Date</th>
                    
                    <th>Description</th>
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
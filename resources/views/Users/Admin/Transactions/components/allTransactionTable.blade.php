<div class="card card-danger">
    <div class="card-header">
        <h3 class="card-title">All Loans' Transactions</h3>
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
                    <th>Loan Date </th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($transactionData as $data)

                <tr>
                    <td>{{$data->loanID}}</td>
                    <td>{{$data->landID}} <br> {{$data->name}} <br> {{$data->NIC}}</td>
                    <td>{{$data->loanAmount}}</td>
                    <td>{{$data->loanDate}}</td>

                    <td class="text-center">

                        <form action="AllTransaction/{{$data->loanID}}" method="post">
                        @csrf
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-list-ul"></i> View All Transactions
                            </button>
                        </form>

                    </td>
                </tr>


                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <th>Loan ID</th>
                    <th>Land ID - Owner - NIC</th>
                    <th>Loan Amount</th>
                    <th>Loan Date </th>

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

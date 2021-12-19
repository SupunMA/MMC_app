<div class="card card-secondary">
    <div class="card-header">

        {{-- Title --}}
        <h3 class="card-title">No Transaction Data</h3>
        @foreach ($transactionData as $data)
            <h3 class="card-title">All Transactions of {{$data->loanID}} - {{$data->name}} - {{$data->NIC}}</h3>
        @break
        @endforeach

    </div>
    <!-- /.card-header -->
    <div class="card-body">

        @include('Users.Admin.messages.addMsg')

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Loan ID - Owner - NIC</th>
                    <th>Paid Date </th>
                    <th>Paid Amount </th>
                    <th>Paid Interest</th>
                    <th>Paid Penalty Fee</th>
                    <th>Rest Interest</th>
                    <th>Rest Penalty Fee</th>
                    <th>Reduced Amount from Loan</th>
                    <th>Extra Amount</th>
                    <th>Details</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($transactionData as $data)

                <tr>

                    <td>{{$data->transID}}</td>
                    <td>{{$data->loanID}} <br> {{$data->name}} <br> {{$data->NIC}}</td>
                    <td>{{$data->paidDate}}</td>
                    <td>{{$data->transPaidAmount}}</td>
                    <td>{{$data->transPaidInterest}}</td>
                    <td>{{$data->transPaidPenaltyFee}}</td>
                    <td>{{$data->transRestInterest}}</td>
                    <td>{{$data->transRestPenaltyFee}}</td>
                    <td>{{$data->transReducedAmount}}</td>
                    <td>{{$data->transExtraMoney}}</td>
                    <td>{{$data->transDetails}}</td>

                    <td class="text-center">
                        <a class="btn btn-danger" type="button" data-toggle="modal"
                            data-target="#loanDeleteModal-{{$data->transID}}">
                            <i class="far fa-trash-alt"></i> Delete
                        </a>
                    </td>
                </tr>

                @include('Users.Admin.Transactions.components.viewTransactions.deleteTransaction')


                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <th>Transaction ID</th>
                    <th>Loan ID - Owner - NIC</th>
                    <th>Paid Date </th>
                    <th>Paid Amount </th>
                    <th>Paid Interest</th>
                    <th>Paid Penalty Fee</th>
                    <th>Rest Interest</th>
                    <th>Rest Penalty Fee</th>
                    <th>Reduced Amount from Loan</th>
                    <th>Extra Amount</th>
                    <th>Details</th>

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

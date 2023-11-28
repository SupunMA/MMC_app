


        {{-- Title --}}

        @forelse ($transactionData as $data)
            <h5> | Name :- {{$data->name}} | NIC :- {{$data->NIC}} | Loan Date :- {{$data->loanDate}}| Loan ID :- {{$data->loanID}}</h5>

            <div class="card card-secondary">
            <div class="card-header">
            <h3 class="card-title">All Transactions</h3>
            @break
        @empty
        <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">No Data</h3>
        @endforelse


    </div>
    <!-- /.card-header -->
    <div class="card-body">

        @include('Users.Admin.messages.addMsg')

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>

                    <th>Date </th>
                    <th>Paid </th>
                    <th>All Paid </th>
                    <th>Paid Interest</th>
                    <th>Paid Late Fee</th>
                    <th>Rest Interest</th>
                    <th>Rest Late Fee</th>
                    <th>Reduced from Loan</th>
                    <th>Extra</th>
                    <th>Details</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($transactionData as $data)

                <tr>

                    <td>{{$data->transID}}</td>

                    <td>{{$data->paidDate}}</td>
                    <td>{{$data->transPaidAmount}}</td>
                    <td>{{$data->transAllPaid}}</td>
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
                    <th>ID</th>

                    <th>Date </th>
                    <th>Paid </th>
                    <th>All Paid </th>
                    <th>Paid Interest</th>
                    <th>Paid Late Fee</th>
                    <th>Rest Interest</th>
                    <th>Rest Late Fee</th>
                    <th>Reduced from Loan</th>
                    <th>Extra </th>
                    <th>Details</th>

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


<!-- DataTables  & Plugins -->

@include('layouts.adminComponents.lib.specific-js.dataTables')
@include('layouts.adminComponents.lib.specific-js.formInput')

@endpush

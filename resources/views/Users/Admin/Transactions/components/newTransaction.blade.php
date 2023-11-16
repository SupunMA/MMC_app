<!-- This form included into addClient Blade -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        {{-- <!-- SELECT2 EXAMPLE --> --}}
        <form action="{{route('admin.addingTransaction')}}" method="post">
            @csrf
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Paying Details</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        {{-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> --}}
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    {{-- Msg after submit --}}
                    @include('Users.Admin.messages.addMsg')

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">


                            <div class="form-group">
                                <label>Select (Loan ID - NIC - Client Name)</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="transLoanID">
                                    {{-- <option selected="selected">Alabama</option> --}}

                                    @foreach ($ClientsWithLoan as $data)
                                    <option value="{{$data->loanID}}">{{$data->loanID}} - {{$data->NIC}} -
                                        {{$data->name}}</option>
                                    @endforeach


                                </select>
                            </div>
                            <!-- /.form-group -->


                            <div class="form-group">
                                <label>Paid Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </div>
                                    <input type="text" min="100000" step="1000.00" value="" name="transPaidAmount"
                                        class="form-control" data-inputmask="'mask': [ '999','9999','99999','999999','9999999','99999999']"
                                        data-mask>
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- /.form-group -->

                             <!-- Date dd/mm/yyyy -->
                             <div class="form-group">
                                <label>Paid Date</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" data-inputmask-alias="datetime" id="dateInput"
                                        name="paidDate" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                        </div>
                        <!-- /.col -->



                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">

                            <!-- Text area -->
                            <div class="form-group">
                                <label>Description (More Details)</label>

                                <div class="input-group">
                                    <textarea name="transDetails" cols="60" rows="3" class="form-control"></textarea>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->



                            {{-- radio --}}
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="reduceLoan">
                                    <label class="custom-control-label" for="customSwitch1">Reduce the Loan Amount</label>
                                    </div>
<br>
                                <h5 class="form-title">If there is extra money ?</h5>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input custom-control-input-danger" value="keep" type="radio" id="Radio1" name="extraMoney" checked>
                                  <label for="Radio1" class="custom-control-label">Keep the extra money until the next payment.</label>
                                </div>

                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input custom-control-input-danger" value="reduce" type="radio" id="Radio2" name="extraMoney">
                                  <label for="Radio2" class="custom-control-label">Reduce extra money from the loan</label>
                                </div>





                        </div>
                        <!--end column-->

                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <small class="form-text text-muted text-right">Please check details again.</small>
                    <button type="submit" class="btn btn-success btn-lg float-right"><b>&nbsp; Save All&nbsp;</b>
                </div>
            </div>
            <!-- /.card -->
        </form>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->


@push('specificJs')

{{-- toastr msg --}}

{{-- toastr auto click --}}
<script type="text/javascript">
    $(document).ready(function () {
        $(".toastrDefaultSuccess").click();
        $(".toastrDefaultError").click();
    });

</script>

{{-- transaction today date --}}
<script>
    // Get the current date
    var currentDate = new Date();

    // Format the date as "YYYY-MM-DD" (assuming you want the same format as your example)
    var formattedDate = currentDate.toISOString().split('T')[0];

    // Set the formatted date as the value for the input element
    document.getElementById('dateInput').value = formattedDate;
</script>

@include('layouts.adminComponents.lib.specific-js.formInput')

@endpush

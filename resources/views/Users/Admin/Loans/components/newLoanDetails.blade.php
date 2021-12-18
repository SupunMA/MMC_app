<!-- This form included into addClient Blade -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        {{-- <!-- SELECT2 EXAMPLE --> --}}
        <form action="{{route('admin.addingLoan')}}" method="post">
            @csrf
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">New Loan Details</h3>

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
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Select (Land ID - NIC - Client Name)</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="loanLandID">
                                    {{-- <option selected="selected">Alabama</option> --}}

                                    @foreach ($ClientsWithLand as $data)
                                    <option value="{{$data->landID}}">{{$data->landID}} - {{$data->NIC}} -
                                        {{$data->name}}</option>
                                    @endforeach


                                </select>
                            </div>
                            <!-- /.form-group -->



                            <div class="form-group">
                                <label>Loan Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </div>
                                    <input type="text" min="100000" step="1000.00" value="" name="loanAmount"
                                        class="form-control" data-inputmask="'mask': [ '999999','9999999','99999999']"
                                        data-mask>
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- /.form-group -->

                        </div>
                        <!-- /.col -->

                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12">
                            <!-- IP mask -->
                            <div class="form-group">
                                <label class="text-danger">Interest Rate (Monthly) </label>

                                <div class="input-group">

                                    <input type="text" class="form-control" placeholder="Percentage : 3.5"
                                        data-inputmask="'mask': ['9.9','99.9']" data-mask name="loanRate">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- IP mask -->
                            <div class="form-group">
                                <label>Penalty Fee Rate (monthly)</label>

                                <div class="input-group">

                                    <input type="text" class="form-control"
                                        data-inputmask="'mask': [ '9.9','99.9','100']" data-mask
                                        placeholder="Percentage : 1" name="penaltyRate">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text ">%</span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->


                        </div>
                        <!--end column-->

                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12">

                            <!-- Date dd/mm/yyyy -->
                            <div class="form-group">
                                <label>Date</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" data-inputmask-alias="datetime"
                                        name="loanDate" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->


                            <!-- Text area -->
                            <div class="form-group">
                                <label>Description (More Details)</label>

                                <div class="input-group">
                                    <textarea name="description" cols="60" rows="3" class="form-control"></textarea>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

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
<script>
    $('.toastrDefaultError').click(function () {
        toastr.error("Could't Save the Data. Please try again")
    });

    $('.toastrDefaultSuccess').click(function () {
        toastr.success('&#160; Saved Successfully!.&#160;')
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

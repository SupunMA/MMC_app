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
                        <div class="col-md-4">
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

                        <div class="col-md-4">
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

                        <div class="col-md-4">



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



                            <div class="form-group">
                                <label>Due Day</label>
                                <select class="form-control" name="dueDate">

                                    <option value="1">1st</option>
                                    <option value="2">2nd</option>
                                    <option value="3">3rd</option>
                                    <option value="4">4th</option>
                                    <option value="5">5th</option>
                                    <option value="6">6th</option>
                                    <option value="7">7th</option>
                                    <option value="8">8th</option>
                                    <option value="9">9th</option>
                                    <option value="10">10th</option>
                                    <option value="11">11th</option>
                                    <option value="12">12th</option>
                                    <option value="13">13th</option>
                                    <option value="14">14th</option>
                                    <option value="15">15th</option>
                                    <option value="16">16th</option>
                                    <option value="17">17th</option>
                                    <option value="18">18th</option>
                                    <option value="19">19th</option>
                                    <option value="20">20th</option>
                                    <option value="21">21st</option>
                                    <option value="22">22nd</option>
                                    <option value="23">23rd</option>
                                    <option value="24">24th</option>
                                    <option value="25">25th</option>
                                    <option value="26">26th</option>
                                    <option value="27">27th</option>
                                    <option value="28">28th</option>
                                    <option value="29">29th</option>
                                    <option value="30">30th</option>

                                </select>
                            </div>


                        </div>
                        <!--end column-->

                    </div>
                    <!-- /.row -->
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

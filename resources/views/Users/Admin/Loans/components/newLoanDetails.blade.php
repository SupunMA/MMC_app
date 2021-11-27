<!-- This form included into addClient Blade -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- SELECT2 EXAMPLE -->
        <div class="card card-success">
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Select (Owner's Name - NIC - Land Address)</label>
                            <select class="form-control select2bs4" style="width: 100%;">
                                {{-- <option selected="selected">Alabama</option> --}}
                                <option>Amal - 88907685 - Colombo</option>
                                <option>Nimal - 88907685 - Gampaha</option>
                                <option>Sumal - 88907685 - Colombo</option>

                            </select>
                        </div>
                        <!-- /.form-group -->


                        {{-- <div class="form-group">
                            <label>Disabled</label>
                            <select class="form-control select2bs4" disabled="disabled" style="width: 100%;">
                                <option selected="selected">Alabama</option>
                                <option>Alaska</option>
                                <option>California</option>
                                
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label>Loan Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rs.</span>
                                </div>
                                <input type="text" min="100000" step="1000.00" value="100000" class="form-control"
                                data-inputmask="'mask': [ '999999','9999999','99999999']" data-mask>
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
                                    data-inputmask="'mask': ['9.9','99.9']" data-mask>
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

                                <input type="text" class="form-control" data-inputmask="'mask': [ '9.9','99.9','100']"
                                    data-mask placeholder="Percentage : 1">
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
                        <div class="form-group">
                            <label>Date:</label>
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input"
                                    data-target="#reservationdate" />
                                <div class="input-group-append" data-target="#reservationdate"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>



                        <div class="form-group">
                            <label>Due Day</label>
                            <select class="form-control">
                                
                                <option>1st</option>
                                <option>2nd</option>
                                <option>3rd</option>
                                <option>4th</option>
                                <option>5th</option>
                                <option>6th</option>
                                <option>7th</option>
                                <option>8th</option>
                                <option>9th</option>
                                <option>10th</option>
                                <option>11th</option>
                                <option>12th</option>
                                <option>13th</option>
                                <option>14th</option>
                                <option>15th</option>
                                <option>16th</option>
                                <option>17th</option>
                                <option>18th</option>
                                <option>19th</option>
                                <option>20th</option>
                                <option>21st</option>
                                <option>22nd</option>
                                <option>23rd</option>
                                <option>24th</option>
                                <option>25th</option>
                                <option>26th</option>
                                <option>27th</option>
                                <option>28th</option>
                                <option>29th</option>
                                <option>30th</option>

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


                        <textarea name="" id="" cols="60" rows="3" class="form-control"></textarea>
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

    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

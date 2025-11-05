<!-- This form included into addClient Blade -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        {{-- <!-- SELECT2 EXAMPLE --> --}}
        <form action="{{route('admin.addingTransaction')}}" method="post">
            @csrf
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">New Transaction Details</h3>

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
                                <select class="form-control select2bs4" style="width: 100%;" name="transLoanID">
                                    {{-- <option selected="selected">Alabama</option> --}}

                                    @foreach ($ClientsWithLoan as $data)
                                    <option value="{{$data->loanID}}">{{$data->loanID}} - {{$data->NIC}} -
                                        {{$data->name}}</option>
                                    @endforeach


                                </select>
                            </div>
                            <!-- /.form-group -->

                            <!-- Loan Details Card (collapsible & expanded info) -->
                            <div class="card card-info" id="loanDetailsCard">
                                <div class="card-header">
                                    <h3 class="card-title">Loan Details</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse/Expand">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <p><strong>Current Principal:</strong> Rs. <span id="loanAmount">0</span></p>
                                            <p><strong>Interest Rate:</strong> <span id="loanRate">0</span>%</p>
                                            <p><strong>Penalty Rate:</strong> <span id="penaltyRate">0</span>%</p>
                                            <p><strong>Loan Date:</strong> <span id="loanDate">-</span></p>
                                            <p><strong>Land ID:</strong> <span id="loanLandID">-</span></p>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <p><strong>Last Payment Date:</strong> <span id="lastPaidDate">-</span></p>
                                            <p><strong>Last Paid Amount:</strong> Rs. <span id="lastPaidAmount">0</span></p>
                                            <p><strong>Total Paid (All):</strong> Rs. <span id="transAllPaid">0</span></p>
                                            <p><strong>Remaining Interest:</strong> Rs. <span id="transRestInterest">0</span></p>
                                            <p><strong>Remaining Penalty:</strong> Rs. <span id="transRestPenaltyFee">0</span></p>
                                        </div>
                                    </div>

                                    <hr />
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Description: <span id="loanDescription">-</span></small>
                                        <button type="button" id="toggleBreakdownBtn" class="btn btn-sm btn-outline-secondary">Show breakdown</button>
                                    </div>

                                    <div id="loanBreakdown" style="display:none; margin-top:12px;">
                                        <h6>Calculation Breakdown</h6>
                                        <ul>
                                            <li><strong>Calculated Interest (up to paid date):</strong> Rs. <span id="calculatedInterest">0</span></li>
                                            <li><strong>Generated Penalty Fee:</strong> Rs. <span id="generatedPenalty">0</span></li>
                                            <li><strong>Extra Money Held:</strong> Rs. <span id="transExtraMoney">0</span></li>
                                            <li><strong>Reduced Amount (principal):</strong> Rs. <span id="transReducedAmount">0</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

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
                                    <input type="text" class="form-control" data-inputmask-alias="datetime"
                                        name="paidDate" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                        </div>
                        <!-- /.col -->



                        <div class="col-md-4">

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
                                <h5 class="form-title">If there is extra money ?</h5>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input custom-control-input-danger" value="keep" type="radio" id="Radio1" name="extraMoney" checked>
                                  <label for="Radio1" class="custom-control-label">Keep the extra money until the next payment.</label>
                                </div>
                                <br>
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

        // Handle loan selection change
        $('select[name="transLoanID"]').on('change', function() {
            var loanId = $(this).val();
            if(loanId) {
                $.ajax({
                    url: '/Admin/getLoanDetails/' + loanId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        // ensure card is visible and expanded when data loads
                        $('#loanDetailsCard').removeClass('collapsed-card');
                        $('#loanDetailsCard').show();

                        function fmt(val){
                            if (val === null || val === undefined) return '0';
                            if (typeof val === 'number') return val.toLocaleString();
                            var n = Number(val);
                            return isFinite(n) ? n.toLocaleString() : val;
                        }

                        $('#loanAmount').text(fmt(data.loanAmount));
                        $('#loanRate').text(data.loanRate ?? '0');
                        $('#penaltyRate').text(data.penaltyRate ?? '0');
                        $('#loanDate').text(data.loanDate ?? '-');
                        $('#loanLandID').text(data.loanLandID ?? '-');
                        $('#loanDescription').text(data.description ?? '-');

                        // populate last transaction info if exists
                        if(data.lastTransaction) {
                            var lt = data.lastTransaction;
                            $('#lastPaidDate').text(lt.paidDate ?? '-');
                            $('#lastPaidAmount').text(fmt(lt.transPaidAmount));
                            $('#transAllPaid').text(fmt(lt.transAllPaid));
                            $('#transRestInterest').text(fmt(lt.transRestInterest));
                            $('#transRestPenaltyFee').text(fmt(lt.transRestPenaltyFee));
                            $('#transExtraMoney').text(fmt(lt.transExtraMoney));
                            $('#transReducedAmount').text(fmt(lt.transReducedAmount));

                            // best-effort populate breakdown values from last transaction
                            $('#calculatedInterest').text(fmt(lt.transRestInterest));
                            $('#generatedPenalty').text(fmt(lt.transRestPenaltyFee));
                        } else {
                            $('#lastPaidDate').text('-');
                            $('#lastPaidAmount').text('0');
                            $('#transAllPaid').text('0');
                            $('#transRestInterest').text('0');
                            $('#transRestPenaltyFee').text('0');
                            $('#transExtraMoney').text('0');
                            $('#transReducedAmount').text('0');
                            $('#calculatedInterest').text('0');
                            $('#generatedPenalty').text('0');
                        }

                        // ensure breakdown toggle works
                        $('#loanDetailsCard').off('click', '#toggleBreakdownBtn').on('click', '#toggleBreakdownBtn', function(){
                            var $b = $('#loanBreakdown');
                            $b.toggle();
                            $(this).text($b.is(':visible') ? 'Hide breakdown' : 'Show breakdown');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching loan details:', error);
                        alert('Error loading loan details. Please try again.');
                    }
                });
            } else {
                // collapse the card when no loan selected
                $('#loanDetailsCard').addClass('collapsed-card');
                // keep it visible but collapsed (AdminLTE handles body visibility with the class)
                $('#loanDetailsCard').show();
            }
        });

        // Trigger change event if a loan is pre-selected
        if($('select[name="transLoanID"]').val()) {
            $('select[name="transLoanID"]').trigger('change');
        }
    });
</script>

@include('layouts.adminComponents.lib.specific-js.formInput')

@endpush

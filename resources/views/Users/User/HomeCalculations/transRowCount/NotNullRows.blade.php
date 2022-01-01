<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
    <div class="info-box shadow">
        <span class="info-box-icon bg-warning"><i class="far fa-handshake"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">
                <h5>ගෙවිය යුතු මුළු මුදල</h5>
            </span>
            <span class="info-box-number">

                <h5><b>රු.@include('Users.User.HomeCalculations.transRowCount.NotNull.interestAndPenaltyFee')</b></h5>

            </span>
        </div>

        <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>


{{-- Transaction --}}
<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
    <div class="info-box shadow">
        <span class="info-box-icon bg-secondary"><i class="fas fa-money-bill"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">
                <h5>හිඟ ණය වාරික එකතුව</h5>
            </span>
            <span class="info-box-number">

                <h5><b>රු.@include('Users.User.HomeCalculations.transRowCount.NotNull.restInterest')</b></h5>

            </span>
        </div>

        <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>


<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
    <div class="info-box shadow">
        <span class="info-box-icon bg-primary"><i class="fas fa-coins"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">
                <h5>හිඟ ප්‍රමාද ගාස්තු එකතුව</h5>
            </span>
            <span class="info-box-number">
                <h5><b>රු.@include('Users.User.HomeCalculations.transRowCount.NotNull.restPenaltyFee')</b></h5>
            </span>
        </div>

        <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>


<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
    <div class="info-box shadow">
        <span class="info-box-icon bg-success"><i class="fas fa-seedling"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">
                <h5>ගෙවූ වාරිකයේ අතිරික්තය</h5>
            </span>
            <span class="info-box-number">
                <h5><b>රු.{{$transactionData->transExtraMoney}}</b></h5>
            </span>
        </div>

        <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>


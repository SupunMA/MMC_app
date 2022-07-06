@extends('layouts.userLayout')
{{-- justify-content-center --}}
@section('content')
<div class="container-fluid">
    
    {{-- Date and Time --}}
    @foreach ($loanData as $item)
        @include('Users.User.HomeCalculations.components.timeDate')
    @endforeach

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">ගෙවීම් තොරතුරු</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        
                        @if ($countTransRows != 0)
                       
                        {{-- Not Null Transaction Rows --}}
                        @include('Users.User.HomeCalculations.transRowCount.NotNullRows')
                        @endif


                        @if ($countTransRows == 0)
    
                        {{-- Null Transaction Rows --}}
                        @include('Users.User.HomeCalculations.transRowCount.ifNullRows')
                        
                        @endif
                        
                       


                        @foreach ($loanData as $item)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                            <div class="info-box shadow">
                                <span class="info-box-icon bg-danger"><i class="fas fa-book-open"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">

                                        <h5>මාසික ණය පොලිය</h5>

                                    </span>
                                    <span class="info-box-number">
                                        <h5><b>රු.{{round($item->loanAmount * $item->loanRate / 100,0)}}</b></h5>
                                    </span>
                                </div>

                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                       
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                            <div class="info-box shadow">
                                <span class="info-box-icon bg-info"><i class="far fa-clock"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">

                                        <h5>දෛනික ප්‍රමාද ගාස්තුව</h5>

                                    </span>
                                    <span class="info-box-number">
                                        <h5>
                                            <b>රු.{{round(($item->loanAmount * $item->penaltyRate/100)/30,0)}}</b>
                                        </h5>
                                    </span>
                                </div>

                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        @endforeach
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    @foreach ($loanData as $item)
    <div class="row">

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
            <div class="callout callout-success">

                <h5>
                    <i class="fas fa-money-bill-wave"></i>
                    &nbsp;&nbsp;
                    <b>ණය තොරතුරු </b>
                </h5>


                <ul class="form-contrl">
                    <li>
                        <h5>ණය මුදල්:- <b>රු.{{$item->loanAmount}}</b></h5>
                    </li>
                    <li>
                        <h5>ණය පොලී ප්‍රතිශතය:- <b>{{$item->loanRate}}%</b></h5>
                    </li>

                    <li>
                        <h5>ප්‍රමාද ගාස්තු ප්‍රතිශතය:- <b>{{$item->penaltyRate}}%</b></h5>
                    </li>
                    <li>
                        <h5>ණය ලබාගත් දිනය:- <b>{{$item->loanDate}}</b></h5>
                    </li>
                </ul>

            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
            <div class="callout callout-warning">

                <h5>
                    <i class="fas fa-home"></i>
                    &nbsp;&nbsp;
                    <b>ඉඩමේ/වත්කමේ තොරතුරු </b>
                </h5>


                <ul class="form-contrl">
                    <li>
                        <h5>වටිනාකම:- <b>රු.{{$item->landValue}}</b> </h5>
                    </li>
                    <li>
                        <h5>ඉඩමේ ලිපිනය:- <br><b>{{$item->landAddress}}</b></h5>
                    </li>
                </ul>

            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 ">
            <div class="callout callout-danger ">
                <h5>
                    <i class="fas fa-address-card"></i>
                    &nbsp;&nbsp;
                    <b>පුද්ගලික තොරතුරු</b>
                </h5>

                <ul class="form-contrl">
                    <li>
                        <h5>නම:- <b>{{$item->name}}</b></h5>
                    </li>
                    <li>
                        <h5>ජා.හැ.අංකය:- <b>{{$item->NIC}} v/x</b></h5>
                    </li>
                    <li>
                        <h5>දුරකතන අංකය:- <b>{{$item->mobile}}</b></h5>
                    </li>
                    <li>
                        <h5>ලිපිනය:- <b>{{$item->address}}</b></h5>
                    </li>
                </ul>

            </div>
        </div>

    </div>
    @endforeach

</div>
@endsection

@section('header')
මගේ ගිණුම -My Account
@endsection

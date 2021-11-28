@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid">

    <div class="col-md-12">
        <div class="row">
           
            <div class="col-lg-3 col-4">
                <!-- small card -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>150</h3>
                        <h2>Clients</h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{route('admin.allClient')}}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-4">
                <!-- small card -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>150</h3>

                        <h2>Lands</h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-house-user"></i>
                    </div>
                    <a href="{{route('admin.allLand')}}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-4">
                <!-- small card -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>150</h3>

                        <h2>Loans</h2> 
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <a href="{{route('admin.allLoan')}}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
    </div>

</div>
@endsection

@section('header')
Dashboard
@endsection

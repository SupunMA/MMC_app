@extends('layouts.adminLayout')

@section('content')


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                <!-- Import Table -->
               @include('Users.Admin.Loans.components.allLoanTable')
            
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>





@endsection

@section('header')
All Loans
@endsection
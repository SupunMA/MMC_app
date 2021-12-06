@extends('layouts.adminLayout')

@section('content')


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                {{-- button to go to add Loan --}}
                <a class="btn btn-danger mb-1" href="{{route('admin.addLoan')}}">
                    <i class="fas fa-list-ul mr-1"></i>
                    <b>Add New Loan</b>
                </a>

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
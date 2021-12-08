@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">

    {{-- button to go to all loans --}}
    <a class="btn btn-danger mb-1" href="{{route('admin.allLoan')}}">
        <i class="fas fa-list-ul mr-1"></i>
        <b>View All Loans</b>
    </a>

    
        
        {{-- <div class="row"> --}}

            {{-- Client Details form --}}
            @include('Users.Admin.Loans.components.newLoanDetails')
           
            {{-- Client Password form --}}
            

        {{-- </div>  --}}
            {{-- End of Row --}}

    
            {{-- End of Form --}}


</div>
@endsection

@section('header')
Add New Loan
@endsection
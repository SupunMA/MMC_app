@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">
    <form method="get" action="">
        @csrf
        
        {{-- <div class="row"> --}}

            {{-- Client Details form --}}
            @include('Users.Admin.Loans.components.newLoanDetails')
           
            {{-- Client Password form --}}
            

        {{-- </div>  --}}
            {{-- End of Row --}}

    </form>
            {{-- End of Form --}}


</div>
@endsection

@section('header')
Add New Loan
@endsection
@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">
    
        
        {{-- <div class="row"> --}}

            {{-- Client Details form --}}
            @include('Users.Admin.Branches.components.allBranchTable')
           
            {{-- Client Password form --}}

        {{-- </div>  --}}
            {{-- End of Row --}}

            {{-- End of Form --}}


</div>
@endsection

@section('header')
All Branches
@endsection
@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">
   
        
        {{-- <div class="row"> --}}

            {{-- Client Details form --}}
            
            @include('Users.Admin.Branches.components.newBranchDetails')
           
            {{-- Client Password form --}}
            
            
        {{-- </div>  --}}
            {{-- End of Row --}}

    
            {{-- End of Form --}}


</div>
@endsection

@section('header')
Add New Branch
@endsection
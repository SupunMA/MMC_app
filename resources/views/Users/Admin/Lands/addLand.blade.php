@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">
    
    {{-- button to go to all lands --}}
    <a class="btn btn-danger mb-1" href="{{route('admin.allLand')}}">
        <i class="fas fa-list-ul mr-1"></i>
        <b>View All Lands</b>
    </a>

    
        
        {{-- <div class="row"> --}}

            {{-- Client Details form --}}
            @include('Users.Admin.Lands.components.newLandDetails')
           
            {{-- Client Password form --}}
            

        {{-- </div>  --}}
            {{-- End of Row --}}

  
            {{-- End of Form --}}


</div>
@endsection

@section('header')
Add New Land
@endsection
@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">
    <form method="get" action="">
        @csrf
        
        <div class="row">

            {{-- Client Details form --}}
            @include('Users.Admin.Clients.components.newClientDetails')
           
            {{-- Client Password form --}}
            @include('Users.Admin.Clients.components.clientPWD')

        </div> 
            {{-- End of Row --}}

    </form>
            {{-- End of Form --}}


</div>
@endsection

@section('header')
Add New Client
@endsection

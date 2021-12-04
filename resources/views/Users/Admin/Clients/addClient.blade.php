@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">

    <form action="{{route('admin.addingClient')}}" method="post">
        @csrf
        <div class="row">

            {{-- Client Details form --}}
            @include('Users.Admin.Clients.components.newClientDetails')

            {{-- Client Password form --}}
            @include('Users.Admin.Clients.components.clientPWD')

        </div>
    </form>
    {{-- End of Row --}}


    {{-- End of Form --}}


</div>
@endsection

@section('header')
Add New Client
@endsection

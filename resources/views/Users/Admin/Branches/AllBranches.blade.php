@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">
    <a class="btn btn-danger mb-1" href="{{route('admin.addBranch')}}">
        <i class="fas fa-list-ul mr-1"></i>
        <b>Add New Branches</b>
    </a>
        {{-- @include('Users.Admin.messages.deleteMsg') --}}
        {{-- <div class="row"> --}}

            {{-- Client Details form --}}
            @include('Users.Admin.Branches.components.allBranchTable')
           
            {{-- Client Password form --}}

        {{-- </div>  --}}
            
            
            

</div>
@endsection

@section('header')
All Branches
@endsection
@extends('layouts.adminLayout')

@section('content')


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- button to go to add client --}}
                <a class="btn btn-danger mb-1" href="{{route('admin.addClient')}}">
                    <i class="fas fa-user-plus mr-1"></i>
                    <b>Add New Clients</b>
                </a>

                <!-- Import Table -->
               @include('Users.Admin.Clients.components.allClientTable')
            
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>





@endsection

@section('header')
All Clients
@endsection

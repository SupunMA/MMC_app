@extends('layouts.adminLayout')

@section('content')


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- button to go to add lands --}}
                <a class="btn btn-danger mb-1" href="{{route('admin.addLand')}}">
                    <i class="fas fa-list-ul mr-1"></i>
                    <b>Add New Lands</b>
                </a>

                <!-- Import Table -->
               @include('Users.Admin.Lands.components.allLandTable')
            
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>





@endsection

@section('header')
All Lands
@endsection
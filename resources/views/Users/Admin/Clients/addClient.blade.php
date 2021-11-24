@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-lg-12 col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">New Client's Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="email" class="form-control" id="" placeholder="Enter Name">
                        </div>



                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Address</label>
                                    <textarea class="form-control" name="" id="" cols="30" rows="4"
                                        placeholder="Address"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12">
                                <label for="exampleInputPassword1">Mobile</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                    </div>
                                    <input type="tel" class="form-control" id="" placeholder="Mobile Number"
                                        pattern="[0-9]{10}">
                                </div>


                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">NIC</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id=""
                                            placeholder="National Identity Card Number" pattern="[0-9]{10}">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><b>V / X</b> </i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Document Number</label>
                                    <input type="text" class="form-control" id="" placeholder="File Number">
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <label for="exampleInputPassword1">Photo URL</label>
                                <div class="input-group mb-3">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-link"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="G-Drive Photo Link">
                                </div>
                            </div>

                            <div class="col-lg-6 col-12">
                                <label for="exampleInputPassword1">Map URL</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Google Map Link">
                                </div>
                            </div>

                        </div>



                    </div>
                    <!-- /.card-body -->



            </div>

        </div>



        <div class="col-lg-12 col-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Client's Login Credentials</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->

                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="tel" class="form-control" id="" placeholder="New Password"
                                    pattern="[0-9]{10}">
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Confirm the Password</label>
                                <input type="tel" class="form-control" id="" placeholder="Type Password Again"
                                    pattern="[0-9]{10}">
                            </div>
                        </div>

                    </div>


                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <div class="form-group">
                        <small class="form-text text-muted">Please check details again.</small>
                        <button type="submit" class="btn btn-danger"><b>&nbsp; Save &nbsp;</b> </button>
                    </div>
                </div>
            </div>
        </div>


        </form>
    </div>

</div>
@endsection

@section('header')
Add New Client
@endsection

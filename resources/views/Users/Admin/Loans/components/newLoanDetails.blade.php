<!-- This form included into addClient Blade -->

<div class="col-lg-6 col-12 ">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">New Loan's Details</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->

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
{{-- End of First Card --}}
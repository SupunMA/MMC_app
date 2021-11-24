 <!-- This form included into addClient Blade -->
 
 {{-- Start of Second Card --}}

 <div class="col-lg-6 col-12">
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
                <small class="form-text text-muted text-right">Please check details again.</small>
                <button type="submit" class="btn btn-danger btn-lg float-right"><b>&nbsp; Save All&nbsp;</b>
                </button>
            </div>
        </div>
    </div>
</div>
{{-- End of Second Card --}}
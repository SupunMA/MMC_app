<!-- This form included into addClient Blade -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- SELECT2 EXAMPLE -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">New Land Details</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    {{-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> --}}
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Select (Owner's Name - NIC)</label>
                            <select class="form-control select2bs4" style="width: 100%;">
                                {{-- <option selected="selected">Alabama</option> --}}
                                <option>Amal - 88907685</option>
                                <option>Nimal - 88907685</option>
                                <option>Sumal - 88907685</option>
                                
                            </select>
                        </div>
                        <!-- /.form-group -->


                        {{-- <div class="form-group">
                            <label>Disabled</label>
                            <select class="form-control select2bs4" disabled="disabled" style="width: 100%;">
                                <option selected="selected">Alabama</option>
                                <option>Alaska</option>
                                <option>California</option>
                                
                            </select>
                        </div> --}}
                        <div class="form-group">
                          <label>Value Of The Land</label>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">Rs.</span>
                              </div>
                              <input type="text" min="100000" step="1000.00" value="100000" class="form-control"
                                data-inputmask="'mask': [ '999999','9999999','99999999']" data-mask>
                              <div class="input-group-append">
                                  <span class="input-group-text">.00</span>
                              </div>
                          </div>
                      </div>

                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->

                    <div class="col-md-6">
                        <!-- IP mask -->
                        <div class="form-group">
                            <label>Map URL </label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-laptop"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask="'alias': 'url'" data-mask placeholder="Google Map Link">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->

                        <!-- Text area -->
                        <div class="form-group">
                          <label>Description (More Details)</label>

                          <div class="input-group">
                             
                              
                              <textarea name="" id="" cols="60" rows="3" class="form-control"></textarea>
                          </div>
                          <!-- /.input group -->
                      </div>
                      <!-- /.form group -->

                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <small class="form-text text-muted text-right">Please check details again.</small>
              <button type="submit" class="btn btn-warning btn-lg float-right"><b>&nbsp; Save All&nbsp;</b>
            </div>
        </div>
        <!-- /.card -->

    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

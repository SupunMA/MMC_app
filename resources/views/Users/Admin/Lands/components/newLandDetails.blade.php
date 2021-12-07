<!-- This form included into addClient Blade -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- SELECT2 EXAMPLE -->
        <form action="{{route('admin.addingLand')}}" method="post">
            @csrf
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

                    {{-- Msg after submit --}}
                    @include('Users.Admin.messages.addMsg')

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select (Owner's Name - NIC)</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="ownerID">
                                    {{-- <option selected="selected">Alabama</option> --}}

                                    @foreach ($clients as $Cdata)
                                    <option value="{{$Cdata->id}}">{{$Cdata->name}} - {{$Cdata->NIC}}</option>
                                    @endforeach



                                </select>
                            </div>
                            <!-- /.form-group -->


                            <div class="form-group">
                                <label>Value Of The Land</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </div>
                                    <input type="text" min="100000" step="1000.00" value="100000" class="form-control"
                                        data-inputmask="'mask': [ '999999','9999999','99999999']" data-mask
                                        name="landValue">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- /.form-group -->


                            <!-- MAP mask -->
                            <div class="form-group">
                                <label>Map URL </label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-laptop"></i></span>
                                    </div>
                                    <input type="text" name="landMap" class="form-control"
                                        data-inputmask="'alias': 'url'" data-mask placeholder="Google Map Link">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->



                        </div>
                        <!-- /.col -->

                        <div class="col-md-4">


                            <!-- Text area -->
                            <div class="form-group">
                                <label>Address of the Land</label>

                                <div class="input-group">
                                    <textarea name="landAddress" rows="3" class="form-control"></textarea>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- Text area -->
                            <div class="form-group">
                                <label>Description (More Details)</label>

                                <div class="input-group">
                                    <textarea name="landDetails" rows="2" class="form-control"></textarea>
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
        </form>

    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->



@push('specificJs')

{{-- toastr msg --}}
<script>
    $('.toastrDefaultError').click(function () {
        toastr.error("Could't Save the Data. Please try again")
    });

    $('.toastrDefaultSuccess').click(function () {
        toastr.success('&#160; Saved Successfully!.&#160;')
    });

</script>

{{-- toastr auto click --}}
<script type="text/javascript">
    $(document).ready(function () {
        $(".toastrDefaultSuccess").click();
        $(".toastrDefaultError").click();
    });

</script>
@endpush
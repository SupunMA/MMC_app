<!-- This form included into addClient Blade -->

<div class="col-lg-6 col-12 ">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">New Client's Details</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->

        <div class="card-body">
            @include('Users.Admin.messages.addMsg')

            <div class="form-group">
                <label >Name</label>
                <input type="name" name="name" class="form-control" id="name" placeholder="Enter Name">
            </div>

            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="form-group">
                        <label >Address</label>
                        <textarea class="form-control" name="address" id="" cols="30" rows="4"
                            placeholder="Address"></textarea>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <label >Mobile</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" data-inputmask='"mask": "(999) 999 9999"' data-mask
                            placeholder="Mobile Number" name="mobile">
                    </div>


                </div>

            </div>

            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="form-group">
                        <label >NIC</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control"
                                data-inputmask="'mask': ['999999999', '999999999999']" data-mask
                                placeholder="National Identity Card Number" name="NIC">
                            <div class="input-group-append">
                                <span class="input-group-text"><b>V / X</b> </i></span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 col-12">
                    <div class="form-group">
                        <label >Document Number</label>
                        <input type="text" class="form-control" name="fileName" placeholder="File Number">
                    </div>
                </div>


            </div>

            <div class="row">
                <div class="col-lg-6 col-12">
                    <label >Photo URL (ID only)</label>
                    <div class="input-group mb-3">

                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                        </div>

                        <input type="text" class="form-control" name="photo" placeholder="G-Drive Photo ID">
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <label >Map URL</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                        </div>
                        <input type="text" class="form-control" data-inputmask="'alias': 'url'"
                        data-mask placeholder="Google Map Link" name="userMap" >
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-lg-6 col-12">
                    <div class="form-group">
                        <label>Select The Branch</label>

                        <select class="form-control select2bs4" style="width: 100%;" name="refBranch">
                            {{-- <option selected="selected">Alabama</option> --}}
                            @foreach ($branches as $bd)
                                <option value="{{$bd->branchID}}">{{$bd->branchName}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

            </div>



        </div>
        <!-- /.card-body -->



    </div>
</div>
{{-- End of First Card --}}

@push('specificJs')

{{-- toastr msg --}}

{{-- toastr auto click --}}
<script type="text/javascript">
    $(document).ready(function () {
        $(".toastrDefaultSuccess").click();
        $(".toastrDefaultError").click();
    });

</script>

@include('layouts.adminComponents.lib.specific-js.formInput')

@endpush

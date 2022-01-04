<!-- Edit land -->
<div class="modal fade" id="landEditModal-{{$data->landID}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update <b>{{$data->name}}</b>'s Land</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('admin.updateLand')}}" method="post">
                @csrf
                
                <div class="modal-body">
                   
                    <input type="hidden" name="landID" value="{{$data->landID}}">
                    
                    
                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Owner's Name - NIC</label>
                               <input type="text" disabled class="form-control" value="{{$data->name}} - {{$data->NIC}}">
                                <i>Owner of the land can not be changed.</i>
                            </div>
                            <!-- /.form-group -->


                            <div class="form-group">
                                <label>Value Of The Land</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </div>
                                    <input type="text" min="100000" step="1000.00" value="{{$data->landValue}}" class="form-control"
                                        data-inputmask="'mask': [ '999999.99','9999999.99','99999999.99']" data-mask
                                        name="landValue">
                             <!--   <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>-->
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
                                    <input type="text" name="landMap" class="form-control" value="{{$data->landMap}}"
                                        data-inputmask="'alias': 'url'" data-mask placeholder="Google Map Link">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                        </div>
                        <!-- /.col -->



                        <div class="col-md-12">

                            <!-- Text area -->
                            <div class="form-group">
                                <label>Address of the Land</label>

                                <div class="input-group">
                                    <textarea name="landAddress" rows="3" class="form-control">{{$data->landAddress}}</textarea>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- Text area -->
                            <div class="form-group">
                                <label>Description (More Details)</label>

                                <div class="input-group">
                                    <textarea name="landDetails" rows="2" class="form-control">{{$data->landDetails}}</textarea>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

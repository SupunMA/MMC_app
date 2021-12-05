<!-- Edit branch -->
<div class="modal fade" id="branchEditModal-{{$bd->branchID}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update <b>{{$bd->branchName}}</b> Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('admin.updateBranch')}}" method="post">
                @csrf
                
                <div class="modal-body">
                   
                    <input type="hidden" name="branchID" value="{{$bd->branchID}}">
                    
                    <div class="form-group">
                        <label>Name of the Branch</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-building"></i></span>
                            </div>
                            <input type="text" placeholder="Branch Name" name="branchName" class="form-control"
                                value="{{$bd->branchName}}">
                        </div>
                    </div>


                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Tele-phone Number</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                            </div>
                            <input type="text" name="branchTP" class="form-control"
                                data-inputmask='"mask": "(999) 999 9999"' data-mask placeholder="Tel Number"
                                value="{{$bd->branchTP}}">
                        </div>
                        <!-- /.input group -->
                    </div>


                    <!-- URL mask -->
                    <div class="form-group">
                        <label>Map URL </label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="text" name="branchLocation" class="form-control"
                                data-inputmask="'alias': 'url'" data-mask placeholder="Google Map Link"
                                value="{{$bd->branchLocation}}">
                        </div>
                        <!-- /.input group -->
                    </div>


                    <div class="form-group">
                        <label>Branch Address</label>

                        <div class="input-group">

                            <textarea name="branchAddress" id="" cols="40" rows="4"
                                class="form-control">{{$bd->branchAddress}}</textarea>
                        </div>
                        <!-- /.input group -->
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

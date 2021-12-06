<!-- Edit branch -->
<div class="modal fade" id="ClientEditModal-{{$client->id}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update <b>{{$client->name}}</b> Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('admin.updateClient')}}" method="post">
                @csrf
                
                <div class="modal-body">
                   
                    <input type="hidden" name="id" value="{{$client->id}}">
                    
                    
                    <div class="form-group">
                        <label >Name</label>
                        <input type="name" name="name" value="{{$client->name}}" class="form-control" placeholder="Enter Name">
                    </div>
        
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label >Address</label>
                                <textarea class="form-control" name="address" cols="30" rows="4"
                                    placeholder="Address">{{$client->address}}</textarea>
                            </div>
                        </div>
        
                        <div class="col-lg-6 col-12">
                            <label >Mobile</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask='"mask": "(999) 999 9999"' data-mask
                                    placeholder="Mobile Number" value="{{$client->mobile}}" name="mobile">
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
                                        placeholder="National Identity Card Number" name="NIC" value="{{$client->NIC}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><b>V / X</b> </i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
        
        
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label >Document Number</label>
                                <input type="text" value="{{$client->fileName}}" class="form-control" name="fileName" placeholder="File Number">
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
        
                                <input type="text" class="form-control" value="{{$client->photo}}" name="photo" placeholder="G-Drive Photo ID">
                            </div>
                        </div>
        
                        <div class="col-lg-6 col-12">
                            <label >Map URL</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask="'alias': 'url'"
                                data-mask placeholder="Google Map Link" name="userMap" value="{{$client->userMap}}">
                            </div>
                        </div>
        
                    </div>
        
                    <div class="row">
        
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label>Select The Branch</label>
                                
                                <select class="form-control select2bs4" style="width: 100%;" name="refBranch">
                                    <option selected="selected" value="{{$client->refBranch}}">Default Branch</option>
                                    @foreach ($branches as $bd)   
                                        <option value="{{$bd->branchID}}">{{$bd->branchName}} Branch</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>
        
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

<!-- Edit land -->
<div class="modal fade" id="loanEditModal-{{$data->loanID}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update <b>{{$data->name}}</b>'s Loan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('admin.updateLoan')}}" method="post">
                @csrf
                
                <div class="modal-body">
                   
                    <input type="hidden" name="loanID" value="{{$data->loanID}}">
                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Land ID - NIC - Client Name</label>
                                <select class="form-control select2bs4" style="width: 100%;" name="loanLandID" disabled>
                                    
                                    <option selected="selected" value="{{$data->landID}}">{{$data->landID}} - {{$data->NIC}} - {{$data->name}}</option>
                                        
                                </select>
                                <i>Land and owner of the loan can not be changed.</i>
                            </div>
                            <!-- /.form-group -->


                            
                            <div class="form-group">
                                <label>Loan Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </div>
                                    <input type="text" min="100000" step="1000.00" name="loanAmount"
                                        class="form-control" data-inputmask="'mask': [ '999999','9999999','99999999']"
                                        data-mask value="{{$data->loanAmount}}">
                               <!-- <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div> -->
                                </div>
                            </div>

                            <!-- /.form-group -->


                            
                        </div>
                        <!-- /.col -->

                        <div class="col-md-12">
                            <!-- IP mask -->
                            <div class="form-group">
                                <label class="text-danger">Interest Rate (Monthly) </label>

                                <div class="input-group">

                                    <input type="text" class="form-control" placeholder="Percentage : 3.5"
                                        data-inputmask="'mask': ['9','9.9','99.9']" data-mask name="loanRate"
                                        value="{{$data->loanRate}}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <!-- IP mask -->
                            <div class="form-group">
                                <label>Penalty Fee Rate (monthly)</label>

                                <div class="input-group">

                                    <input type="text" class="form-control"
                                        data-inputmask="'mask': [ '9','9.9','99.9']" data-mask
                                        placeholder="Percentage : 1" name="penaltyRate" value="{{$data->penaltyRate}}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text ">%</span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->




                        </div>
                        <!--end column-->

                        <div class="col-md-12">



                            <!-- Date dd/mm/yyyy -->
                            <div class="form-group">
                                <label>Date</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->loanDate}}" data-inputmask-alias="datetime"
                                        name="loanDate" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->



                            <div class="form-group">
                                <label>Due Day</label>
                                <select class="form-control" name="dueDate" value="{{$data->dueDate}}">
                                    <option value="{{$data->dueDate}}" selected>Default Due Day {{$data->dueDate}}</option>

                                    <option value="1">1st</option>
                                    <option value="2">2nd</option>
                                    <option value="3">3rd</option>
                                    <option value="4">4th</option>
                                    <option value="5">5th</option>
                                    <option value="6">6th</option>
                                    <option value="7">7th</option>
                                    <option value="8">8th</option>
                                    <option value="9">9th</option>
                                    <option value="10">10th</option>
                                    <option value="11">11th</option>
                                    <option value="12">12th</option>
                                    <option value="13">13th</option>
                                    <option value="14">14th</option>
                                    <option value="15">15th</option>
                                    <option value="16">16th</option>
                                    <option value="17">17th</option>
                                    <option value="18">18th</option>
                                    <option value="19">19th</option>
                                    <option value="20">20th</option>
                                    <option value="21">21st</option>
                                    <option value="22">22nd</option>
                                    <option value="23">23rd</option>
                                    <option value="24">24th</option>
                                    <option value="25">25th</option>
                                    <option value="26">26th</option>
                                    <option value="27">27th</option>
                                    <option value="28">28th</option>
                                    <option value="29">29th</option>
                                    <option value="30">30th</option>

                                </select>
                            </div>


                        </div>
                        <!--end column-->

                    <!-- Text area -->
                    <div class="form-group">
                        <label>Description (More Details)</label>

                        <div class="input-group">
                            <textarea name="description" cols="60" rows="3" class="form-control">{{$data->description}}</textarea>
                        </div>
                        <!-- /.input group -->
                    </div>
                    <!-- /.form group -->








                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

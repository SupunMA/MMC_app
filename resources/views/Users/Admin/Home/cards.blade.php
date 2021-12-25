
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ">
    <div class="card card-dark">
        <div class="card-header">

             <h3 class="card-title">Calculated Details</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                
                <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                    <!-- small card -->
                    <div class="small-box bg-primary">
                        <div class="inner">
        
                            <h3>{{$ClientsCount}}</h3>
                            <h2>Clients</h2>
        
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{route('admin.allClient')}}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
        
                <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                    <!-- small card -->
                    <div class="small-box bg-warning">
                        <div class="inner">
        
                            <h3>{{$LandCount}}</h3>
                            <h2>Lands</h2>
        
                        </div>
                        <div class="icon">
                            <i class="fas fa-house-user"></i>
                        </div>
                        <a href="{{route('admin.allLand')}}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
        
                <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                    <!-- small card -->
                    <div class="small-box bg-danger">
                        <div class="inner">
        
                            <h3>{{$LoanCount}}</h3>
                            <h2>Loans</h2> 
        
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <a href="{{route('admin.allLoan')}}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
        
                <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                    <!-- small card -->
                    <div class="small-box bg-success">
                        <div class="inner">
        
                            <h3>{{$BranchesCount}}</h3>
                            <h2>Branches</h2> 
                            
                        </div>
                        <div class="icon">
                            <i class="far fa-building"></i>
                        </div>
                        <a href="{{route('admin.allBranch')}}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

            </div>


            <hr>


            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <!-- small card -->
                    <div class="small-box bg-light">
                        <div class="inner">
        
                            <h3>{{$TransCount}}</h3>
                            <h2>Transactions</h2> 
                            
                        </div>
                        <div class="icon">
                            <i class="far fa-building"></i>
                        </div>
                        <a href="{{route('admin.allTransaction')}}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

            </div>


        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
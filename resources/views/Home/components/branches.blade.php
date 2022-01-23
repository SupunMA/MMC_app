<!--Available Branches-->
<section class="container"  class="section-bg">
    <br>
    <h1 class="section-header text-center" style="font-family: 'Nunito', 'sans-serif';">Available Branches</h1>
    <br>
    <div class="row justify-content-center">

        @foreach ($bdata as $oneBranch )
            
            <div class="card mr-3 mb-3 wow fadeInUp" style="width: 18rem;">
                <img class="card-img-top" src="homePage/img/logot.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">
                        <h4>{{$oneBranch->branchName}}</h4>
                    </h5>
                    <p class="card-text">
                        <ul>
                            <li><strong>Address:- {{$oneBranch->branchAddress}}</strong> </li>
                            <li><strong>Telephone:- {{$oneBranch->branchTP}}</strong> </li>
                        </ul>
                    </p>
                    <a href="{{$oneBranch->branchLocation}}" target="_blank" class="btn btn-primary"><i class="ion-ios-location"></i> &nbsp; View on map</a>
                </div>
            </div>

        @endforeach
        
    </div>
    <br>
</section>
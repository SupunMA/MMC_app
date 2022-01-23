<section id="intro" class="clearfix">
    <div class="container">

        <div class="intro-img">
            <img src="homePage/img/logot.png" alt="" class="img-fluid">
        </div>

        <div class="intro-info">
            
            <h2>We<br> keep our promises,<br>We<br>give you hope</h2>
            <div>
                @if (Route::has('login'))
                <div class="well">
                    @auth
                    <a href="{{ route('admin.home') }}" class="btn-get-started">My Account</a>
                    @else
                    <a href="{{ route('login') }}" class="btn-get-started">Login</a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-services">Register</a>
                    @endif
                    @endif
                </div>
                @endif
            </div>





        </div>

    </div>
</section><!-- #intro -->
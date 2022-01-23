<header id="header" class="fixed-top">
    <div class="container">

        <div class="logo float-left">
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <h1 class="text-light"><a href="#header"><span>NewBiz</span></a></h1> -->
            <a href="#intro" class="scrollto"><img src="homePage/img/logo.png" alt="" class="img-fluid"></a>
        </div>


        <nav class="main-nav float-right d-none d-lg-block">
            <ul>
                <li class="active"><a href="/#intro">Home</a></li>
                <li><a href="/#about">About Us</a></li>
                <li><a href="/#services">Services</a></li>
               <!-- <li><a href="/lands">Lands For Sale</a></li> -->

               <!-- <li><a href="/#team">Board Of Directors</a></li> -->

               <!-- <li><a href="/#testimonials">Testimonials</a></li>-->

                <li><a href="/#contact">Contact Us</a></li>


                @if (Route::has('login'))

                @auth
                <li> <a href="{{ route('admin.home') }}">My Account</a></li>
                @else
                <li><a href="{{ route('login') }}">Login</a></li>
                @if (Route::has('register'))
                <li><a href="#">Register</a></li>
                @endif
                @endauth

                @endif


            </ul>
        </nav><!-- .main-nav -->

    </div>
</header><!-- #header -->
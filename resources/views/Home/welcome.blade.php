<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Madhushanka MicroCredit</title>

    <!-- style -->
    @include('Home.components.cssJs.style')

    <!-- Fav icon -->
    @include('Home.components.cssJs.fav')



</head>

<body>

    <!--Header-->
    @include('Home.components.header')

    <!--Intro Section-->
    @include('Home.components.intro')

    <main id="main">

        <!--About Us Section-->
       @include('Home.components.about')

        <!--Services Section-->
        @include('Home.components.services')

        <!--Why Us Section-->
        @include('Home.components.why')
        
        <!--Portfolio Section-->
        {{-- @include('Home.components.Portfolio') --}}

        <!--testimonials-->
        {{-- @include('Home.components.testimonials') --}}

        <!--Team Section=-->
        {{-- @include('Home.components.team') --}}

        <!--Clients Section-->
        {{-- @include('Home.components.clients') --}}

        <br>
        <!--Available Branches-->
        @include('Home.components.branches')

        <!-- contact -->
        @include('Home.components.contact')



    </main>



    <!-- footer -->
    @include('Home.components.footer')

    <!--Back to Top-->
    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

    <!-- preloader -->
    <div id="preloader"></div>

    <!-- javaScripts -->
    @include('Home.components.cssJs.js')


</body>


</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Madhushanka MicroCredit</title>

    <!-- style -->
    @include('home.components.cssJs.style')

    <!-- Fav icon -->
    @include('home.components.cssJs.fav')



</head>

<body>

    <!--Header-->
    @include('home.components.header')

    <!--Intro Section-->
    @include('home.components.intro')

    <main id="main">

        <!--About Us Section-->
       @include('home.components.about')

        <!--Services Section-->
        @include('home.components.services')

        <!--Why Us Section-->
        @include('home.components.why')
        
        <!--Portfolio Section-->
        @include('home.components.Portfolio')

        <!--testimonials-->
        @include('home.components.testimonials')

        <!--Team Section=-->
        @include('home.components.team')

        <!--Clients Section-->
        @include('home.components.clients')

        <br>
        <!--Available Branches-->
        @include('home.components.branches')

        <!-- contact -->
        @include('home.components.contact')



    </main>



    <!-- footer -->
    @include('home.components.footer')

    <!--Back to Top-->
    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

    <!-- preloader -->
    <div id="preloader"></div>

    <!-- javaScripts -->
    @include('home.components.cssJs.js')


</body>


</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Madhushanka Micro Credit</title>

    <!-- Fav Icon -->
    @include('CDN_Css_Js.exImages.favIcon')
    <!-- Styles -->
    <style>

    </style>

    @include('CDN_Css_Js.Css.bootcss')


</head>

<body>
    @include('Home.components.nav')
    
    <div class="container">
        <h1 class="display-4 text-center">Madhushanka Micro Credit (Pvt) Ltd</h1>

        @if (Route::has('login'))
        <div class="text-center">
            @auth
            <a class="btn btn-success" href="{{ route('admin.home') }}">Home</a>
            @else
            <a class="btn btn-primary" href="{{ route('login') }}">Log in</a>

            @if (Route::has('register'))
            <a class="btn btn-warning" href="{{ route('register') }}">Register</a>
            @endif
            @endauth
        </div>
        @endif
        <br>
        @include('Home.components.carousel')


    </div>
    @include('CDN_Css_Js.Js.bootjs')

</body>

</html>

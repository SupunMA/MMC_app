<!DOCTYPE html>
<!--
* OOPS - Perfect 404 pages Pack
* Build Date: October 2016
* Last Update: October 2016
* Author: Madeon08 for ThemeHelite
* Copyright (C) 2016 ThemeHelite
* This is a premium product available exclusively here : http://themeforest.net/user/Madeon08/portfolio
* -->
<html lang="en-us" class="no-js">

	<head>
		<meta charset="utf-8">
		<title>MMC - Page Expired</title>
		<meta name="description" content="The description should optimally be between 150-160 characters.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="ThemeHelite">

        <!-- ================= Favicons ================== -->
        <!-- Standard -->
        @include('CDN_Css_Js.exImages.favIcon')
       

		<!-- ============== Resources style ============== -->
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('errorPages/Css/404.css'); }}" />
	</head>

	<body>

        <!-- Your logo on the top left -->
       {{-- {{ url()->previous() }} --}}
            <a href="{{ route('welcome') }}" class="logo-link" title="back home">
                <img src="https://drive.google.com/uc?export=view&id=17oMlgpisY5HADXWqOh9jfu5WzDANcE_f" class="logo" alt="Company's logo" />
            </a>
        
        
        <div class="content">

            <div class="content-box">
                
                

                <!-- Your text -->
                <h1>Oops! 419 Page Expired.</h1>

                <p>We think your session Expired.<br>
                    Please try logging in again.</p>
                     

            </div>

        </div>

        <footer class="light">

            <ul>
                <li><a href="{{ route('welcome') }}">Home Page</a></li>

                <li><a href="#">Search</a></li>

                <li><a href="#">Help</a></li>

                <li><a href="#">Trust & Safety</a></li>

                <li><a href="#">Sitemap</a></li>

                <li><a href="#"><i class="fa fa-facebook"></i></a></li>

                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
            </ul>

        </footer>

       

    </body>

</html>
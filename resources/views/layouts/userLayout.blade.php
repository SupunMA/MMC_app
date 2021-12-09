<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Client | MMC (Pvt) Ltd</title>

        <!-- Fav Icon -->
        @include('CDN_Css_Js.exImages.favIcon')

        <!-- Style -->
        @include('layouts.adminComponents.lib.Style')

    </head>

    <body class="hold-transition sidebar-mini layout-navbar-fixed">
        <!-- layout-footer-fixed   -->
        <div class="wrapper">

            <!-- Navbar -->
            @include('layouts.userComponents.navBar')


            <!-- Main Side Bar -->
            @include('layouts.userComponents.sideBar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">

                                    @yield('header')

                                </h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                                    <li class="breadcrumb-item active">

                                        @yield('header')

                                    </li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">

                        @yield('content')


                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->


            <!-- Control Sidebar -->
            @include('layouts.adminComponents.controlSlideBar')

            <!-- /.control-sidebar -->

            <!-- Main Footer -->
            @include('layouts.adminComponents.footer')


        </div>
        <!-- ./wrapper -->

        <!-- Logout Modal and Form -->
        @include('layouts.adminComponents.modal.logoutModal')

        <!-- Js -->
        @include('layouts.adminComponents.lib.js')
        {{--  --}}


    </body>

</html>

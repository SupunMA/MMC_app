<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | MMC (Pvt) Ltd</title>
    
    <!-- Fav Icon -->
    @include('Bootstrap.exImages.favIcon')
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   
        <!-- Font Awesome Icons -->
    <link rel="stylesheet" href={{ URL::asset('plugins/fontawesome-free/css/all.min.css'); }}>
   
    <!-- Theme style -->
    <link rel="stylesheet" href={{ URL::asset('dist/css/adminlte.min.css'); }}>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user1-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user8-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user3-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i
                                                class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzRjGD_g9AixRcn3q4Iz-ToEIElnNZ4KdOOg&usqp=CAU"
                    alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Admin</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://yt3.ggpht.com/ytc/AKedOLRJ7vDbP2EUGugOC5RpY5WwbegndXfVGUxnxFiOHA=s88-c-k-c0x00ffffff-no-rj"
                            class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                          <a href="{{ route('admin.home') }}" class="nav-link {{ Route::currentRouteNamed('admin.home') ? 'active' : ' ' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>Dashboard
                                  <span class="right badge badge-danger">Live</span>
                              </p>
                          </a>
                        </li>


{{-- Clients --}}
                        <li class="nav-item {{ Route::currentRouteNamed('admin.addClient') || Route::currentRouteNamed('admin.allClient') ? 'menu-open' : 'menu-close' }}">
                            <a href="#" class="nav-link {{ Route::currentRouteNamed('admin.addClient') || Route::currentRouteNamed('admin.allClient') ? 'active' : '' }} ">
                              <i class="fas fa-users"></i>
                                <p>
                                    Clients
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.addClient') }}" class="nav-link {{ Route::currentRouteNamed('admin.addClient') ? 'active' : '' }} ">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add New Client</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.allClient') }}" class="nav-link {{ Route::currentRouteNamed('admin.allClient') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Clients</p>
                                    </a>
                                </li>
                            </ul>
                        </li>


{{-- Lands --}}

                        <li class="nav-item {{ Route::currentRouteNamed('admin.addLand') || Route::currentRouteNamed('admin.allLand') ? 'menu-open' : 'menu-close' }}">
                          <a href="#" class="nav-link {{ Route::currentRouteNamed('admin.addLand') || Route::currentRouteNamed('admin.allLand') ? 'active' : '' }}">
                            <i class="fas fa-house-user"></i>
                              <p>
                                  Lands
                                  <i class="right fas fa-angle-left"></i>
                              </p>
                          </a>
                          <ul class="nav nav-treeview">
                              <li class="nav-item">
                                  <a href="{{ route('admin.addLand') }}" class="nav-link {{ Route::currentRouteNamed('admin.addLand') ? 'active' : '' }}">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p> Add New Land</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('admin.allLand') }}" class="nav-link {{ Route::currentRouteNamed('admin.allLand') ? 'active' : '' }}">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p> All Lands</p>
                                  </a>
                              </li>
                          </ul>
                      </li>


{{-- Loans --}}

                      <li class="nav-item {{ Route::currentRouteNamed('admin.addLoan') || Route::currentRouteNamed('admin.allLoan') ? 'menu-open' : 'menu-close' }}">
                        <a href="#" class="nav-link {{ Route::currentRouteNamed('admin.addLoan') || Route::currentRouteNamed('admin.allLoan') ? 'active' : '' }}">
                          <i class="fas fa-money-bill-wave"></i>
                            <p>
                                Loans
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.addLoan') }}" class="nav-link {{ Route::currentRouteNamed('admin.addLoan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Add New Loan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.allLoan') }}" class="nav-link {{ Route::currentRouteNamed('admin.allLoan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> All Loans</p>
                                </a>
                            </li>
                        </ul>
                    </li>



                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-university"></i>
                                <p>
                                    Capital
                                    <span class="right badge badge-warning">Money</span>
                                </p>
                            </a>
                        </li>

                        {{-- Logout --}}
                        <li class="nav-item">
                            <!-- Logout modal trigger Button -->
                            <a href="#" class="nav-link btn btn-danger" data-toggle="modal" data-target="#staticBackdrop">                
                            <i class="fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>                         
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

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
                                <li class="breadcrumb-item"><a href="/Account/Admin">Home</a></li>
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
                   
                   
                    {{-- <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>

                                    <p class="card-text">
                                        Some quick example text to build on the card title and make up the bulk of the
                                        card's
                                        content.
                                    </p>

                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div>

                            <div class="card card-primary card-outline">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>

                                    <p class="card-text">
                                        Some quick example text to build on the card title and make up the bulk of the
                                        card's
                                        content.
                                    </p>
                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div><!-- /.card -->
                        </div>
                        <!-- /.col-md-6 -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="m-0">Featured</h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">Special title treatment</h6>

                                    <p class="card-text">With supporting text below as a natural lead-in to additional
                                        content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>

                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Featured</h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">Special title treatment</h6>

                                    <p class="card-text">With supporting text below as a natural lead-in to additional
                                        content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div> --}}
                    <!-- /.row -->


                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                No Plug, Just Play! 
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2021 <a href="https://thakshane.lk" target="_blank">Thakshane.lk</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->




  
  <!-- Logout Modal -->
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Madhushanka Micro Credit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
         <h4>Do you want to Logout ?..</h4>
          <i>Please confirm it again.</i> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

          <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          
          <i class="fas fa-sign-out-alt"></i>

              Logout

          </a>

        </div>
      </div>
    </div>
  </div>

    <!-- Logout -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>



    <!-- jQuery -->
    <script src={{ URL::asset('plugins/jquery/jquery.min.js'); }}></script>
    <!-- Bootstrap 4 -->
    <script src={{ URL::asset('plugins/bootstrap/js/bootstrap.bundle.min.js'); }}></script>
    <!-- AdminLTE App -->
    <script src={{ URL::asset('dist/js/adminlte.min.js'); }}></script>

    <!-- Modal js -->
    <script>
    $('#myModal').on('shown.bs.modal', function () 
    {
        $('#myInput').trigger('focus')
    })
    </script>
    
</body>

</html>

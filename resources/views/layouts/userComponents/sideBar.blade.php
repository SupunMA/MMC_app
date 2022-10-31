<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.home')}}" class="brand-link">
        <img src="https://drive.google.com/uc?export=view&id=17oMlgpisY5HADXWqOh9jfu5WzDANcE_f"
            alt="MMC Logo" class="brand-image img-circle elevation-3" style="opacity: 1">
        <span class="brand-text font-weight-light">Client Account</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://drive.google.com/uc?export=view&id={{ Auth::user()->photo }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}
        
        
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
                {{-- <li class="nav-item {{ Route::currentRouteNamed('admin.addClient') || Route::currentRouteNamed('admin.allClient') ? 'menu-open' : 'menu-close' }}">
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
                </li> --}}


                    {{-- Lands --}}

                {{-- <li class="nav-item {{ Route::currentRouteNamed('admin.addLand') || Route::currentRouteNamed('admin.allLand') ? 'menu-open' : 'menu-close' }}">
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
              </li> --}}


                {{-- Loans --}}

              {{-- <li class="nav-item {{ Route::currentRouteNamed('admin.addLoan') || Route::currentRouteNamed('admin.allLoan') ? 'menu-open' : 'menu-close' }}">
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
            </li> --}}


            {{-- Branches --}}

            {{-- <li class="nav-item {{ Route::currentRouteNamed('admin.addBranch') || Route::currentRouteNamed('admin.allBranch') ? 'menu-open' : 'menu-close' }}">
                <a href="#" class="nav-link {{ Route::currentRouteNamed('admin.addBranch') || Route::currentRouteNamed('admin.allBranch') ? 'active' : '' }}">
                    <i class="far fa-building"></i>
                    <p>
                        Branches
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.addBranch') }}" class="nav-link {{ Route::currentRouteNamed('admin.addBranch') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p> Add New Branch</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.allBranch') }}" class="nav-link {{ Route::currentRouteNamed('admin.allBranch') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p> All Branches</p>
                        </a>
                    </li>
                </ul>
            </li> --}}



                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-university"></i>
                        <p>
                            Capital
                            <span class="right badge badge-warning">Money</span>
                        </p>
                    </a>
                </li> --}}

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
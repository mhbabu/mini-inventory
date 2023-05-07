<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <div class="image">
                    <b class="pr-1">{{ auth()->user()->name }}</b>
                        <img width="30" src="{{ url('/assets/backend/img/profile.png') }}" class="img-circle elevation-2" alt="User Image">
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg-right">
                <a class="dropdown-item" href="{{ url('logout') }}"><i class="fas fa-user-lock mr-2"></i> Logout </a>
            </div>
        </li>
    </ul>
</nav>

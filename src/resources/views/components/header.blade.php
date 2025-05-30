<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        {{-- <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                class="nav-link nav-link-lg message-toggle beep"><i class="fa fa-bell" aria-hidden="true"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Pemberitahuan
                </div>
                <div class="dropdown-list-content dropdown-list-message">
                    <table id="table-notification" class="dropdown-item">
                        <tr>
                            <td><b>Kusnaedi</b></td>
                        </tr>
                        <tr>
                            <td>Hello, Bro!</td>
                        </tr>
                        <tr>
                            <td>
                                <hr style="width: 23em">
                            </td>
                        </tr>
                        <tr>
                            <td><b>Kusnaedi</b></td>
                        </tr>
                        <tr>
                            <td>Hello, Bro!</td>
                        </tr>
                    </table>
                </div>
            </div>
        </li> --}}
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('img/avatar') . '/' . Auth::user()->avatar }}"
                    class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->fullname }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                {{-- <div class="dropdown-title">Logged in 5 min ago</div> --}}
                <a href="{{ route('profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

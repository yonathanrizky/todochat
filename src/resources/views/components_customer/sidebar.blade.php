<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', env('APP_NAME')) }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', env('APP_NAME')) }}</a>
        </div>
        <ul class="sidebar-menu">

            <li class="menu-header">Menu</li>
            <li class="nav-item dropdown {{ $type_menu === 'menu' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-list"></i><span>Menu</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('chat') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ url('chat') }}">Chat</a>
                    </li>

                    <li class='{{ Request::is('complain') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ url('complain') }}">Laporan Keluhan</a>
                    </li>
                </ul>
            </li>

        </ul>

        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="{{ route('web.logout') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Logout
            </a>
        </div>
    </aside>
</div>

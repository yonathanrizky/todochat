<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">{{ str_replace('_', ' ', env('APP_NAME')) }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">{{ str_replace('_', ' ', env('APP_NAME')) }}</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ $type_menu === 'admin.dashboard' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('admin.dashboard') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                </ul>
            </li>

            <li class="menu-header">Master</li>
            <li class="nav-item dropdown {{ $type_menu === 'memo' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-sticky-note"></i><span>Master
                        Data</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('memo-in') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ url('memo-in') }}">Data Keluhan</a>
                    </li>
                    <li class='{{ Request::is('customer') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ url('admin/customer') }}">Data Customer</a>
                    </li>
                </ul>
            </li>

            @if (Auth::user()->admin)
                <li class="menu-header">Parameter</li>
                <li class="nav-item dropdown {{ $type_menu === 'parameter' ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i
                            class="fas fa-list"></i><span>Parameter</span></a>
                    <ul class="dropdown-menu">
                        <li class='{{ Request::is('user') ? 'active' : '' }}'>
                            <a class="nav-link" href="{{ url('admin/user') }}">Pengguna</a>
                        </li>

                        <li class='{{ Request::is('product') ? 'active' : '' }}'>
                            <a class="nav-link" href="{{ url('admin/product') }}">Produk</a>
                        </li>

                        <li class='{{ Request::is('config-app') ? 'active' : '' }}'>
                            <a class="nav-link" href="{{ url('admin/config-app') }}">Pengaturan</a>
                        </li>
                    </ul>
                </li>
            @endif


        </ul>

        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="{{ route('logout') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Logout
            </a>
        </div>
    </aside>
</div>

<link rel="stylesheet" href="{{ asset('css/admin/admin-sidebar.css') }}" />
<aside class="admin-sidebar dash-sidebar">
    <div class="admin-sidebar-brand">
        <div class="admin-signed-in">Admin</div>
        <div class="admin-brand-mark">
            <span class="brand-ai">ai</span><span class="brand-rest">ZAP CREATIVES</span>
        </div>
    </div>
    <nav class="admin-nav">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}" class="btn btn-outline {{ request()->routeIs('admin.dashboard') ? 'is-primary' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('admin.bookings.index') }}" class="btn btn-outline {{ request()->routeIs('admin.bookings.index') ? 'is-primary' : '' }}">Bookings</a></li>
            <li>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline {{ request()->routeIs('admin.contacts.index') ? 'is-primary' : '' }}">
                    Messages
                    <span id="sidebar-messages-badge" class="admin-nav-badge{{ empty($adminUnreadMessagesCount) ? ' hidden' : '' }}" data-count="{{ $adminUnreadMessagesCount ?? 0 }}">{{ $adminUnreadMessagesCount ?? '' }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-outline {{ request()->routeIs('admin.packages.index') ? 'is-primary' : '' }}">
                    Packages
                    <span id="sidebar-packages-badge" class="admin-nav-badge{{ empty($adminUnreadPackagesCount) ? ' hidden' : '' }}" data-count="{{ $adminUnreadPackagesCount ?? 0 }}">{{ $adminUnreadPackagesCount ?? '' }}</span>
                </a>
            </li>
            <li><a href="{{ route('admin.projects.create') }}" class="btn btn-outline {{ request()->routeIs('admin.projects.create') ? 'is-primary' : '' }}">Add Video</a></li>
            <li><a href="{{ route('admin.password.change') }}" class="btn btn-outline {{ request()->routeIs('admin.password.change') ? 'is-primary' : '' }}">Account Settings</a></li>
        </ul>
    </nav>

    <div class="admin-sidebar-footer">
        <form method="post" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-logout">Logout</button>
        </form>
    </div>
</aside>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <x-nav-link :to="route('ADMIN_TOP')" class="brand-link text-center">
        <span class="brand-text font-weight-light">Intern PHP Source</span>
    </x-nav-link>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <x-nav-link :to="route('ADMIN_USER_SEARCH')" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>User</p>
                    </x-nav-link>
                </li>
            </ul>
        </nav>
    </div>
</aside>

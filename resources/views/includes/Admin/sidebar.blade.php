<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-header">Admin PANEL</li>
        <li class="nav-item">
            <a href="pages/calendar.html" class="nav-link">
                <i class="nav-icon far fa-regular fa-newspaper"></i>
                <p>
                    Posts
                    <span class="badge badge-info right">{{$posts->total()}}</span>
                </p>
            </a>
        </li>

    </ul>
</nav>

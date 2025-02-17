<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="active">
                    <a href="{{ route('home') }}"><img src="assets/img/icons/dashboard.svg" alt="img"><span> Dashboard</span> </a>
                </li>
                
                <li>
                    <a href="{{ route('city') }}"><i class="fas fa-city"></i><span> City</span> </a>
                </li>
                <li>
                    <a href="{{ route('Area') }}"><i class="fas fa-building"></i><span> Areas</span> </a>
                </li>
                <li>
                    <a href="{{ route('Distributor') }}"><i class="fas fa-users"></i><span> Distributor</span> </a>
                </li>

                <li>
                    <a href="{{ route('category') }}"><i class="fas fa-box"></i><span> Category</span> </a>
                </li>

                <li>
                    <a href="{{ route('sub-category') }}"><i class="fas fa-boxes"></i><span> Sub-Category</span> </a>
                </li>

                <li>
                    <a href="{{ route('size') }}"><i class="fas fa-oil-can"></i> <span> Size </span> </a>
                </li>

                <li>
                    <a href="{{ route('product') }}"><i class="fas fa-box-open"></i> <span> Product </span> </a>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-store"></i><span> Purchase</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('Purchase') }}">Add Purchase</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
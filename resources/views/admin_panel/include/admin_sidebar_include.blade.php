<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="active">
                    <a href="{{ route('home') }}"><i class="fas fa-home"></i><span> Dashboard</span> </a>
                </li>

                <li>
                    <a href="{{ route('city') }}"><i class="fas fa-city"></i><span> City</span> </a>
                </li>
                <li>
                    <a href="{{ route('Area') }}"><i class="fas fa-building"></i><span> Areas</span> </a>
                </li>
                
                
                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-users"></i><span> Distributor</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('Distributor') }}">Distributor</a></li>
                        <li><a href="{{ route('Distributor-ledger') }}">Distributor Ledger </a></li>
                        <li><a href="{{ route('Distributor-recovery') }}">Distributor Recoveries </a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-users"></i><span> Vendors</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('vendors') }}">Vendors</a></li>
                        <li><a href="#">Vendors Ledger </a></li>
                        <li><a href="#">Vendors Recoveries </a></li>
                    </ul>
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
                    <a href="{{ route('business_type') }}"><i class="fas fa-oil-can"></i> <span> Business Type </span> </a>
                </li>

                <li>
                    <a href="{{ route('product') }}"><i class="fas fa-box-open"></i> <span> Product </span> </a>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-store"></i><span> Purchase</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('Purchase') }}">Add Purchase</a></li>
                        <li><a href="{{ route('all-Purchases') }}">All Purchase</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-store"></i><span> Sale</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('add-sale') }}">Add Sale</a></li>
                        <li><a href="{{ route('all-sale') }}">Sales</a></li>
                    </ul>
                </li>
                
                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-store"></i><span> Staff Management</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('designation') }}">Add Designation </a></li>
                        <li><a href="{{ route('salesmen') }}">Add Staff</a></li>

                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-store"></i><span> Expenses</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('expense') }}">Add Expense Categroy</a></li>
                        <li><a href="{{ route('add-expenses') }}">Add Expenss</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-store"></i><span>Customer Management</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('customer') }}">Add Cutomers </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
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
                    <a href="javascript:void(0);"><i class="fas fa-user-friends"></i><span> Vendors</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('vendors') }}">Vendors</a></li>
                        <li><a href="{{ route('vendors-ledger') }}">Vendors Ledger </a></li>
                        <li><a href="{{ route('amount-paid-vendors') }}">Vendors Payments </a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('category') }}"><i class="fas fa-box"></i><span> Category</span> </a>
                </li>

                <li>
                    <a href="{{ route('sub-category') }}"><i class="fas fa-boxes"></i><span> Sub-Category</span> </a>
                </li>

                <li>
                    <a href="{{ route('size') }}"><i class="fas fa-wine-bottle"></i> <span> Size </span> </a>
                </li>

                <li>
                    <a href="{{ route('business_type') }}"><i class="fas fa-business-time"></i> <span> Business Type </span> </a>
                </li>

                <li>
                    <a href="{{ route('product') }}"><i class="fas fa-box-open"></i> <span> Product </span> </a>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-shopping-basket"></i><span> Purchase</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('Purchase') }}">Add Purchase</a></li>
                        <li><a href="{{ route('all-Purchases') }}">All Purchase</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-store"></i><span> Distributor Sale</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('add-sale') }}">Add Sale</a></li>
                        <li><a href="{{ route('all-sale') }}">Sales</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-shopping-bag"></i><span> Local Sale</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('local-sale') }}">Add Sale</a></li>
                        <li><a href="{{ route('all-local-sale') }}">Sales</a></li>
                    </ul>
                </li>
                
                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-user-tie"></i><span> Staff Management</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <!-- <li><a href="{{ route('designation') }}">Add Designation </a></li> -->
                        <li><a href="{{ route('salesmen') }}">Add Staff</a></li>

                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-money-bill-wave"></i><span> Expenses</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('expense') }}">Add Expense Categroy</a></li>
                        <li><a href="{{ route('add-expenses') }}">Add Expenss</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-address-book"></i><span>Customer Management</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('customer') }}">Add Cutomers </a></li>
                        <li><a href="{{ route('customer-ledger') }}">Cutomers Payments </a></li>
                        <li><a href="{{ route('customer-recovery') }}">Cutomers Recoveries </a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i class="fas fa-chart-pie"></i><span>Reporting</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('stock-Record') }}">Item Stock Report </a></li>
                        <li><a href="{{ route('Distributor-Ledger-Record') }}">Distributor Ledger Record </a></li>
                        <li><a href="{{ route('Customer-Ledger-Record') }}">Customer Ledger Record </a></li>
                    </ul>
                </li>

                

            </ul>
        </div>
    </div>
</div>
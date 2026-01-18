<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">
        <div class="user-details">
            <div class="d-flex">
                <div class="me-2">
                    <img src="{{ asset('assets/images/users/avatar-4.jpg') }}" alt="" class="avatar-md rounded-circle">
                </div>
                <div class="user-info w-100">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ auth()->user()->name }}
                            <i class="mdi mdi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="mdi mdi-account-circle text-muted me-2"></i>Profile
                            </a></li>
                            @if(auth()->user()->isAdmin())
                            <li><a href="{{ route('admin.settings') }}" class="dropdown-item">
                                <i class="mdi mdi-cog text-muted me-2"></i>Settings
                            </a></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="mdi mdi-power text-muted me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <p class="text-white-50 m-0">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Main</li>

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="mdi mdi-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                <!-- POS Menu -->
                <li>
                    <a href="{{ route('pos.index') }}" class="waves-effect {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                        <i class="mdi mdi-point-of-sale"></i>
                        <span>Point of Sale</span>
                    </a>
                </li>

                <!-- Inventory Menu -->
                <li class="menu-title">Inventory</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <span>Products</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('products.index') }}">All Products</a></li>
                        @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('products.create') }}">Add Product</a></li>
                        @endif
                        <li><a href="{{ route('products.index', ['filter' => 'low_stock']) }}">Low Stock</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-tag-multiple"></i>
                        <span>Categories</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('categories.index') }}">All Categories</a></li>
                        @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('categories.create') }}">Add Category</a></li>
                        @endif
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-label"></i>
                        <span>Brands</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('brands.index') }}">All Brands</a></li>
                        @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('brands.create') }}">Add Brand</a></li>
                        @endif
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-truck-delivery"></i>
                        <span>Suppliers</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('suppliers.index') }}">All Suppliers</a></li>
                        @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('suppliers.create') }}">Add Supplier</a></li>
                        @endif
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-account-group"></i>
                        <span>Customers</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('customers.index') }}">All Customers</a></li>
                        @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('customers.create') }}">Add Customer</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Sales & Purchases -->
                <li class="menu-title">Transactions</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-cart"></i>
                        <span>Sales</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('sales.index') }}">All Sales</a></li>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        <li><a href="{{ route('pos.index') }}">New Sale</a></li>
                        @endif
                    </ul>
                </li>

                @if(auth()->user()->isAdmin())
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-cart-plus"></i>
                        <span>Purchases</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('purchases.index') }}">All Purchases</a></li>
                        <li><a href="{{ route('purchases.create') }}">New Purchase</a></li>
                    </ul>
                </li>
                @endif

                <!-- Reports (Admin & Manager only) -->
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                <li class="menu-title">Reports</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-chart-line"></i>
                        <span>Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('reports.sales') }}">Sales Report</a></li>
                        <li><a href="{{ route('reports.inventory') }}">Inventory Report</a></li>
                        <li><a href="{{ route('reports.profit-loss') }}">Profit & Loss</a></li>
                    </ul>
                </li>
                @endif

                <!-- Admin Settings -->
                @if(auth()->user()->isAdmin())
                <li class="menu-title">Administration</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-cog"></i>
                        <span>Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin.users') }}">User Management</a></li>
                        <li><a href="{{ route('admin.settings') }}">System Settings</a></li>
                    </ul>
                </li>
                @endif

            </ul>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->

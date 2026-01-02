<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">

            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm-dark.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="24">
                    </span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm-light.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="24">
                    </span>
                </a>
            </div>

            <!-- Menu Icon -->
            <button type="button" class="btn px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="mdi mdi-menu"></i>
            </button>

            <!-- Create New Dropdown (Admin only) -->
            @if(auth()->user()->isAdmin())
            <div class="dropdown d-none d-lg-inline-block align-self-center">
                <button class="btn btn-header waves-effect  dropdown-toggle" type="button" id="createNewDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Create New<i class="mdi mdi-chevron-down ms-2"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="createNewDropdown">
                    <li><a class="dropdown-item" href="{{ route('products.create') }}">New Product</a></li>
                    <li><a class="dropdown-item" href="{{ route('suppliers.create') }}">New Supplier</a></li>
                    <li><a class="dropdown-item" href="{{ route('customers.create') }}">New Customer</a></li>
                    <li><a class="dropdown-item" href="{{ route('pos.index') }}">New Sale</a></li>
                </ul>
            </div>
            @endif
        </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3" method="GET" action="{{ route('products.index') }}">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search products..."
                                    aria-label="Search products" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- App Search -->
            <form class="app-search d-none d-lg-block" method="GET" action="{{ route('products.index') }}">
                <div class="position-relative">
                    <input type="text" class="form-control border-0" name="search" placeholder="Search products..." value="{{ request('search') }}">
                    <span class="mdi mdi-magnify"></span>
                </div>
            </form>

            <!-- Notification Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="mdi mdi-bell"></i>
                    <span class="badge bg-info rounded-pill">{{ \App\Models\Product::where('stock_quantity', '<=', \DB::raw('min_stock_level'))->count() }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <h5 class="p-3 text-dark mb-0">Low Stock Alerts</h5>
                    <div data-simplebar style="max-height: 230px;">
                        @foreach(\App\Models\Product::where('stock_quantity', '<=', \DB::raw('min_stock_level'))->limit(5)->get() as $product)
                        <a href="{{ route('products.show', $product) }}" class="text-reset notification-item">
                            <div class="d-flex mt-3">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-warning rounded-circle font-size-16">
                                        <i class="mdi mdi-package-variant-closed"></i>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">Stock: {{ $product->stock_quantity }} (Min: {{ $product->min_stock_level }})</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <div class="p-2 d-grid">
                        <a class="font-size-14 text-center" href="{{ route('products.index', ['filter' => 'low_stock']) }}">View all alerts</a>
                    </div>
                </div>
            </div>

            <!-- User -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-4.jpg') }}"
                        alt="Header Avatar">
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="mdi mdi-account-circle font-size-16 align-middle me-2 text-muted"></i>
                        <span>Profile</span>
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a class="dropdown-item" href="{{ route('admin.settings') }}">
                        <i class="mdi mdi-wrench font-size-16 align-middle text-muted me-2"></i>
                        <span>Settings</span>
                    </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-primary">
                            <i class="mdi mdi-power font-size-16 align-middle me-2 text-primary"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Setting -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="mdi mdi-cog bx-spin"></i>
                </button>
            </div>

        </div>
    </div>
</header>

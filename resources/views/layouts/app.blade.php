<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.name'))</title>
    
    <!-- Bootstrap CSS (ديناميكي حسب الاتجاه) -->
    @if(in_array(app()->getLocale(), ['ar', 'ku']))
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(-5px);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-left: 10px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        
        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stat-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stat-card.danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "←";
        }

        /* Select2 Custom Styles */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 8px;
            border: 1px solid #ced4da;
            min-height: 38px;
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            padding: 6px 12px;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            padding-left: 0;
            padding-right: 20px;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 10px;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: 8px;
            border: 1px solid #ced4da;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 8px 12px;
            font-family: 'Cairo', sans-serif;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            padding: 8px 12px;
            font-family: 'Cairo', sans-serif;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* RTL Support for Select2 */
        [dir="rtl"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 20px;
            padding-right: 0;
            text-align: right;
        }

        [dir="rtl"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            left: 10px;
            right: auto;
        }

        [dir="rtl"] .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            text-align: right;
        }

        /* Loading state */
        .select2-container--bootstrap-5 .select2-selection--single.select2-selection--loading {
            background-image: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Custom Select2 Result Templates */
        .select2-result-customer,
        .select2-result-item,
        .select2-result-order,
        .select2-result-supplier {
            padding: 8px 12px;
        }

        .select2-result-customer__title,
        .select2-result-item__title,
        .select2-result-order__title,
        .select2-result-supplier__title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 2px;
        }

        .select2-result-customer__description,
        .select2-result-item__description,
        .select2-result-order__description,
        .select2-result-supplier__description {
            font-size: 0.875rem;
            color: #6c757d;
            font-style: italic;
        }

        /* Select2 with icons */
        .select2-selection__rendered .select2-icon {
            margin-left: 8px;
            color: #6c757d;
        }

        /* Select2 loading animation */
        .select2-container--bootstrap-5 .select2-results__option--loading {
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        /* Select2 no results */
        .select2-container--bootstrap-5 .select2-results__option[aria-live="polite"] {
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        /* Select2 focus state */
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Select2 error state */
        .select2-container--bootstrap-5 .select2-selection.is-invalid {
            border-color: #dc3545;
        }

        .select2-container--bootstrap-5 .select2-selection.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3">
                        <h4 class="text-white text-center mb-4">
                            <i class="fas fa-pills me-2"></i>
                            {{ __('navigation.system_name') }}
                        </h4>
                        
                        <nav class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                {{ __('navigation.dashboard') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                                {{ __('navigation.orders') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}">
                                <i class="fas fa-pills"></i>
                                {{ __('navigation.items') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}" href="{{ route('warehouses.index') }}">
                                <i class="fas fa-warehouse"></i>
                                {{ __('navigation.warehouses') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                                <i class="fas fa-file-invoice"></i>
                                {{ __('navigation.invoices') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('collections.*') ? 'active' : '' }}" href="{{ route('collections.index') }}">
                                <i class="fas fa-money-bill-wave"></i>
                                {{ __('navigation.collections') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                                <i class="fas fa-truck"></i>
                                {{ __('navigation.suppliers') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i>
                                {{ __('navigation.users') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                                <i class="fas fa-shield-alt"></i>
                                {{ __('navigation.permissions') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('returns.*') ? 'active' : '' }}" href="{{ route('returns.index') }}">
                                <i class="fas fa-undo"></i>
                                {{ __('navigation.returns') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar"></i>
                                {{ __('navigation.reports') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('hr.*') ? 'active' : '' }}" href="{{ route('hr.index') }}">
                                <i class="fas fa-users-cog"></i>
                                {{ __('navigation.hr') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                                <i class="fas fa-user-friends"></i>
                                {{ __('navigation.customers') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('finance.*') ? 'active' : '' }}" href="{{ route('finance.dashboard') }}">
                                <i class="fas fa-chart-line"></i>
                                {{ __('navigation.finance') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('regulatory-affairs.*') ? 'active' : '' }}" href="{{ route('regulatory-affairs.dashboard') }}">
                                <i class="fas fa-shield-alt"></i>
                                {{ __('navigation.regulatory_affairs') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('medical-rep.*') ? 'active' : '' }}" href="{{ route('medical-rep.dashboard') }}">
                                <i class="fas fa-user-tie"></i>
                                {{ __('navigation.medical_representatives') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('ai.*') ? 'active' : '' }}" href="{{ route('ai.dashboard') }}">
                                <i class="fas fa-brain"></i>
                                {{ __('navigation.ai') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('backup.*') ? 'active' : '' }}" href="{{ route('backup.index') }}">
                                <i class="fas fa-database"></i>
                                {{ __('navigation.backup') }}
                            </a>

                            <a class="nav-link {{ request()->routeIs('help.*') ? 'active' : '' }}" href="{{ route('help.index') }}">
                                <i class="fas fa-question-circle"></i>
                                {{ __('navigation.help') }}
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                    <div class="container-fluid">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                        
                        <div class="navbar-nav ms-auto d-flex align-items-center">
                            <!-- رابط المساعدة -->
                            <a class="nav-link" href="{{ route('help.index') }}" title="{{ __('navigation.help') }}">
                                <i class="fas fa-question-circle"></i>
                                <span class="d-none d-md-inline">{{ __('navigation.help') }}</span>
                            </a>

                            <!-- مبدل اللغة -->
                            @include('components.language-switcher')

                            <!-- قائمة المستخدم -->
                            <div class="nav-item dropdown ms-3">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-2"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>{{ __('navigation.profile') }}</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>{{ __('navigation.settings') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>{{ __('navigation.logout') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                
                <div class="main-content">
                    @endif
                    
                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- Page Content -->
                    @yield('content')
                    
                    @auth
                </div>
            </div>
            @endauth
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Select2 Configuration -->
    <script src="{{ asset('js/select2-config.js') }}"></script>

    @stack('scripts')
</body>
</html>

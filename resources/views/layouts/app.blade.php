<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TokoKita - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            min-height: 100vh;
        }

        .mobile-navbar {
            display: none;
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            justify-content: space-between;
            align-items: center;
        }

        .sidebar {
            height: 100vh;
            width: 280px;
            background-color: #f6fafd;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .sidebar .navbar-brand {
            font-family: 'Jost', sans-serif;
            font-size: 1.5rem;
            color: #0a1931;
            display: block;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a.nav-link {
            display: flex;
            align-items: center;
            padding: 18px 20px;
            color: #0a1931;
            text-decoration: none;
            border-left: 5px solid transparent;
            transition: all 0.3s ease-in-out;
        }

        .sidebar a.nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .sidebar a.nav-link:hover,
        .sidebar a.nav-link.active {
            background-color: #f7f2eb;
            border-left-color: #334eac;
            color: #334eac;
            font-weight: 500;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
            flex-grow: 1;
            background-color: #f0f3fa;
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }

        .btn-primary {
            background-color: #334eac;
            border-color: #334eac;
            border-radius: 0.5rem;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .alert {
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }

        .chart-container {
            background-color: #ffffff;
            border-radius: 0.75rem;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* responsive */
        @media (max-width: 991.98px) {

            .mobile-navbar {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);           
                box-shadow: none;                
                border-right: none;
                padding-top: 20px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: 20px;
                padding-top: 80px;
            }

            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1010;
                display: none;
            }

            .overlay.show {
                display: block;
            }

            h1 {
                font-size: 1.8rem;
                margin-bottom: 20px;
            }

            .card-header {
                font-size: 1rem;
                padding: 15px 20px;
            }

            .card-body {
                padding: 20px;
            }

            .table th,
            .table td {
                padding: 12px;
                font-size: 0.9rem;
            }

            .btn {
                padding: 10px 18px;
                font-size: 0.9rem;
            }

            .form-control,
            .form-select {
                padding: 10px 12px;
            }

            .alert {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .chart-container {
                height: 35vh !important;
                padding: 15px;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <nav class="mobile-navbar">
        <button class="btn btn-outline-secondary" type="button" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand text-dark fw-bold m-0" href="{{ url('/') }}">TokoKita</a>
    </nav>
    
    <div class="d-flex">
        <div class="sidebar" id="appSidebar">
            <a class="navbar-brand" href="{{ url('/') }}"><i class="fa-solid fa-shop"></i> TokoKita</a>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}">
                        <i class="fas fa-box-open"></i> Manajemen Barang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('stocks.create') ? 'active' : '' }}" href="{{ route('stocks.create') }}">
                        <i class="fas fa-boxes"></i> Tambah Stok Baru
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('purchases.create') ? 'active' : '' }}" href="{{ route('purchases.create') }}">
                        <i class="fas fa-shopping-cart"></i> Catat Pembelian
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('purchases.index') ? 'active' : '' }}" href="{{ route('purchases.index') }}">
                        <i class="fas fa-clipboard-list"></i> Daftar Pembelian
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('sales.create') ? 'active' : '' }}" href="{{ route('sales.create') }}">
                        <i class="fas fa-cash-register"></i> Catat Penjualan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('sales.index') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                        <i class="fas fa-file-invoice-dollar"></i> Daftar Penjualan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('reports.monthly_profit') ? 'active' : '' }}" href="{{ route('reports.monthly_profit') }}">
                        <i class="fas fa-chart-line"></i> Laporan Laba & Stok
                    </a>
                </li>
            </ul>
        </div>
        <div class="content">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Pastikan semua kolom terisi dengan benar.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const appSidebar = document.getElementById('appSidebar');
            const appContent = document.getElementById('appContent');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            sidebarToggle.addEventListener('click', function() {
                appSidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });

            sidebarOverlay.addEventListener('click', function() {
                appSidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            // Close sidebar if a nav link is clicked (useful for mobile)
            appSidebar.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    // Check if on mobile/tablet (less than 992px width)
                    if (window.innerWidth < 992) {
                        appSidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    }
                });
            });

            // Adjust layout on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) { 
                    appSidebar.classList.remove('show'); 
                    sidebarOverlay.classList.remove('show'); 
                    appContent.style.marginLeft = '260px'; 
                } else { 
                    appContent.style.marginLeft = '0'; 
                }
            });

            // Initial adjustment on load
            if (window.innerWidth < 992) {
                appContent.style.marginLeft = '0';
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
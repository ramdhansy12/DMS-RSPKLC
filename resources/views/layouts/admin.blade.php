<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DMS RS')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            width: 250px;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .text,
        .sidebar .logo-text {
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .text,
        .sidebar.collapsed .logo-text {
            opacity: 0;
            pointer-events: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: #333;
        }

        .nav-link:hover {
            background-color: #f1f5ff;
            color: #0d6efd;
        }

        .nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
            . border-left: 4px solid #0d6efd;
        }

        .icon {
            font-size: 18px;
        }
    </style>
</head>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Link CSS Bootstrap atau lainnya -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- TAMBAHKAN BARIS INI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Link CSS kustom Anda -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{--
    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            width: 250px;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .text,
        .sidebar .logo-text {
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .text,
        .sidebar.collapsed .logo-text {
            opacity: 0;
            pointer-events: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: #333;
        }

        .nav-link:hover {
            background-color: #f1f5ff;
            color: #0d6efd;
        }

        .nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
            . border-left: 4px solid #0d6efd;
        }

        .icon {
            font-size: 18px;
        }

        /* Table enterprise look */
        .table {
            border: 1px solid #e5e7eb;
        }

        .table thead th {
            background: #f8fafc;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f5f9;
            transform: scale(1.002);
        }

        /* Badge auto style */
        .badge-status {
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 8px;
        }

        table.dataTable tbody tr:hover {
            background-color: #f1f7ff !important;
            transition: 0.2s ease;
        }
    </style> --}}
</head>

<body>

    <!-- TOPBAR -->
    <nav class="navbar navbar-light bg-light border-bottom px-3">
        <button id="hamburger" class="btn btn-outline-primary">
            ☰
        </button>
        <span class="fw-bold ms-3">Document Management System</span>

        <!-- Right menu -->
        <div class="dropdown ms-auto">
            <a class="dropdown-toggle text-decoration-none d-flex align-items-center gap-2 text-dark" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-5"></i>
                <span>Hello, {{ auth()->user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2">
                <li>
                    <a class="dropdown-item" href="/profile">
                        <i class="bi bi-person me-2"></i> Profil
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    </nav>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div id="sidebar" class="sidebar bg-white border-end vh-100 p-3">
            <h5 class="logo text-primary fw-bold mb-4 text-center">
                🏥 <span class="logo-text">DMS RS</span>
            </h5>

            <ul class="nav flex-column gap-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <span class="icon">📊</span>
                        <span class="text">Dashboard</span>
                    </a>

                </li>

                <li>
                    <a href="{{ route('documents.index') }}"
                        class="nav-link {{ request()->is('documents*') ? 'active' : '' }}">
                        <span class="icon">📄</span>
                        <span class="text">File Dokumen</span>
                    </a>
                </li>

                <li>
                    <a href="/profile" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                        <span class="icon">📄</span>
                        <span class="text">Profile</span>
                    </a>
                </li>
            </ul>
        </div>


        <!-- CONTENT -->
        <div class="flex-fill p-4">
            @yield('content')
        </div>

    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger');

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

</body>
@include('components.footer')

</html>

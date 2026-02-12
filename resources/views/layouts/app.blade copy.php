<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DMS RS')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
    body {
        overflow-x: hidden;
        background-color: #f4f6f9;
    }

    .sidebar {
        width: 250px;
        transition: all 0.3s ease;
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar.collapsed .text,
    .sidebar.collapsed .logo-text {
        display: none;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 8px;
        transition: all 0.2s ease;
        color: #495057;
        font-weight: 500;
    }

    .nav-link:hover {
        background: #e9f2ff;
        color: #0d6efd;
    }

    .nav-link.active {
        background: #dbeafe;
        color: #0d6efd;
        font-weight: 600;
        border-left: 4px solid #0d6efd;
    }

    .icon {
        width: 20px;
        text-align: center;
    }

    .content-area {
        min-height: 100vh;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            z-index: 1050;
            height: 100%;
            left: -250px;
        }

        .sidebar.show {
            left: 0;
        }
    }
    </style>
</head>

<body>

    <!-- TOPBAR -->
    <nav class="navbar navbar-light bg-white border-bottom px-3 shadow-sm">
        <button id="hamburger" class="btn btn-outline-primary">
            <i class="fa fa-bars"></i>
        </button>

        <span class="fw-bold ms-3">🏥 Document Management System</span>

        <div class="dropdown ms-auto">
            <a class="dropdown-toggle text-decoration-none text-dark d-flex align-items-center gap-2" href="#"
                role="button" data-bs-toggle="dropdown">
                <i class="fa-solid fa-user-circle"></i>
                <span>Hello, {{ auth()->user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fa fa-user me-2"></i> Profile
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div id="sidebar" class="sidebar bg-white border-end p-3">

            <h5 class="text-primary fw-bold mb-4 text-center">
                <i class="fa-solid fa-hospital"></i>
                <span class="logo-text"> DMS RS</span>
            </h5>

            <ul class="nav flex-column gap-2">

                <li>
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <span class="icon"><i class="fa fa-chart-line"></i></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('documents.index') }}"
                        class="nav-link {{ request()->is('documents*') ? 'active' : '' }}">
                        <span class="icon"><i class="fa fa-file-lines"></i></span>
                        <span class="text">File Dokumen</span>
                    </a>
                </li>

                {{-- MENU USER (ADMIN ONLY) --}}
                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <span class="icon"><i class="fa fa-users"></i></span>
                        <span class="text">Manajemen User</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('profile.edit') }}"
                        class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                        <span class="icon"><i class="fa fa-id-card"></i></span>
                        <span class="text">Profile</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- CONTENT -->
        <div class="flex-fill p-4 content-area">
            @yield('content')
        </div>

    </div>

    <!-- LOGOUT FORM -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script>
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');

    hamburger.addEventListener('click', () => {

        // Mobile mode
        if (window.innerWidth < 768) {
            sidebar.classList.toggle('show');
        } else {
            sidebar.classList.toggle('collapsed');
        }

    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @include('components.footer')

</body>

</html>

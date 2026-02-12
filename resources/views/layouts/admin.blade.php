<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DMS RS. Permata Keluarga Lippo')</title>

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

        /* Overlay untuk mobile */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .overlay.show {
            display: block;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            z-index: 1050;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .text,
        .sidebar.collapsed .logo-text {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 10px;
        }

        .sidebar.collapsed .nav-link .icon {
            margin: 0;
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
            white-space: nowrap;
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

        /* PERUBAHAN: Style khusus untuk link logout */
        .nav-link.text-danger:hover {
            background: #fff5f5;
            color: #dc3545;
        }

        .icon {
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .content-area {
            min-height: 100vh;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100%;
                left: -250px;
                transition: left 0.3s ease-in-out;
            }

            .sidebar.show {
                left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Overlay untuk mobile -->
    <div id="overlay" class="overlay"></div>

    <!-- TOPBAR -->
    <nav class="navbar navbar-light bg-white border-bottom px-3 shadow-sm">
        <button id="hamburger" class="btn btn-outline-primary">
            <i class="fa fa-bars"></i>
        </button>

        <span class="fw-bold ms-3">🏥 Document Management System</span>

        <!-- PERUBAHAN: Hanya menampilkan nama user, tanpa dropdown -->
        <div class="ms-auto">
            <span class="text-muted">Hallo,<span> {{ auth()->user()->name }}</span>
                <i class="fa-solid fa-user-circle"></i>
            </span>
        </div>
    </nav>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div id="sidebar" class="sidebar bg-white border-end p-3">

            <h5 class="text-success fw-bold mb-4 text-center">
                <i class="fa-solid fa-hospital"></i>
                <span class="logo-text"> DMS RS. Permata Keluarga Lippo</span>
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
                @if (auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-users"></i></span>
                            <span class="text">Manajemen User</span>
                        </a>
                    </li>
                @endif

                <!-- PERUBAHAN: Tombol logout dipindahkan kembali ke sidebar -->
                <li>
                    <a class="nav-link text-danger" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="icon"><i class="fa fa-sign-out-alt"></i></span>
                        <span class="text">Logout</span>
                    </a>
                </li>

                {{-- @if (auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('activity.index') }}"
                            class="nav-link {{ request()->is('activity-logs*') ? 'active' : '' }}">
                            <span class="icon">📜</span>
                            <span class="text">Activity Log</span>
                        </a>
                    </li>
                @endif --}}


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
        const overlay = document.getElementById('overlay');

        function toggleSidebar() {
            // Mobile mode
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }

        hamburger.addEventListener('click', toggleSidebar);

        // Menutup sidebar saat overlay diklik (mobile)
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Menangani perubahan ukuran jendela
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @include('components.footer')

    @stack('scripts')
</body>

</html>

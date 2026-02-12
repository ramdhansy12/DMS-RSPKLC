<nav class="navbar bg-light shadow-sm px-3">
    <button id="btnHamburger" class="btn btn-outline-primary d-md-none">
        <i class="bi bi-list"></i>
    </button>

    <button id="btnCollapse" class="btn btn-outline-secondary d-none d-md-inline">
        <i class="bi bi-layout-sidebar-inset"></i>
    </button>

    <!-- Right menu -->
    <div class="dropdown ms-auto">
        <a class="dropdown-toggle text-decoration-none d-flex align-items-center gap-2 text-dark" href="#"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-5"></i>
            <span>Hello, {{ auth()->user()->name }}</span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2">
            {{-- <li>
                <a class="dropdown-item" href="{{ route('documents.publicPreview') }}">
                    <i class="bi bi-person me-2"></i> Profil
                </a>
            </li> --}}
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


<!-- Content -->
<div class="container-fluid mt-4">
    @yield('content')
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

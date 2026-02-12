<nav class="navbar navbar-light bg-light border-bottom px-3">
    <button class="btn btn-outline-primary" id="menu-toggle">
        ☰
    </button>

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

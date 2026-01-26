<nav class="navbar navbar-light bg-white border-bottom px-4">
    <span class="navbar-text">
        Sistem Manajemen Dokumen Rumah Sakit
    </span>

    <div class="dropdown">
        <a class="dropdown-toggle text-decoration-none" href="#" data-bs-toggle="dropdown">
            {{ auth()->user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Profil</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <form method="POST" action="/logout">
                    @csrf
                    <button class="dropdown-item text-danger">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</nav>

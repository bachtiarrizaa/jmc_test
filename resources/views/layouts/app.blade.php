<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'JMC Employee System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/app-style.css') }}">
</head>

<body>

    <aside id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-layer-group"></i></div>
            <span class="brand-text fw-bold fs-4">JMC System</span>
        </a>

        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="{{ route('dashboard') }}" class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-house"></i>
                    <span class="menu-text">Beranda</span>
                </a>
            </li>

            <li
                class="menu-item {{ request()->is('departments*') || request()->is('positions*') || request()->is('employee-types*') || request()->is('roles*') ? 'open' : '' }}">
                <div class="menu-link" onclick="toggleSubmenu(this)">
                    <i class="fas fa-database"></i>
                    <span class="menu-text">Master Data</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </div>
                <ul class="submenu"
                    style="{{ request()->is('departments*') || request()->is('positions*') || request()->is('employee-types*') || request()->is('roles*') ? 'display:block;' : '' }}">
                    @can('roles.index')
                        <li><a href="{{ route('roles.index') }}"
                                class="submenu-link {{ request()->is('roles*') ? 'active' : '' }}">Role & Permission</a>
                        </li>
                    @endcan
                    <li><a href="{{ route('employee-types.index') }}"
                            class="submenu-link {{ request()->is('employee-types*') ? 'active' : '' }}">Tipe Pegawai</a>
                    </li>
                    <li><a href="{{ route('departments.index') }}"
                            class="submenu-link {{ request()->is('departments*') ? 'active' : '' }}">Departemen</a></li>
                    <li><a href="{{ route('positions.index') }}"
                            class="submenu-link {{ request()->is('positions*') ? 'active' : '' }}">Jabatan</a></li>
                </ul>
            </li>

            @can('employees.index')
                <li class="menu-item">
                    <a href="{{ route('employees.index') }}" class="menu-link {{ request()->is('employees*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="menu-text">Data Pegawai</span>
                    </a>
                </li>
            @endcan

            @if(auth()->user()->can('transport_settings.index') || auth()->user()->can('transport_allowances.index'))
                <li class="menu-item {{ request()->is('transport*') ? 'open' : '' }}">
                    <div class="menu-link" onclick="toggleSubmenu(this)">
                        <i class="fas fa-car-side"></i>
                        <span class="menu-text">Tunjangan Transport</span>
                        <i class="fas fa-chevron-right arrow"></i>
                    </div>
                    <ul class="submenu" style="{{ request()->is('transport*') ? 'display:block;' : '' }}">
                        @can('transport_settings.index')
                            <li><a href="{{ route('transport-settings.index') }}"
                                    class="submenu-link {{ request()->is('transport/settings*') ? 'active' : '' }}">Pengaturan Tarif</a>
                            </li>
                        @endcan
                        @can('transport_allowances.index')
                            <li><a href="{{ route('transport-allowances.index') }}"
                                    class="submenu-link {{ request()->is('transport/allowances*') ? 'active' : '' }}">Data Tunjangan</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @can('activity_logs.index')
                <li class="menu-item">
                    <a href="{{ route('activity-logs.index') }}"
                        class="menu-link {{ request()->is('activity-logs*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span class="menu-text">Log Aktivitas</span>
                    </a>
                </li>
            @endcan
        </ul>

        <div class="p-3 border-top mt-auto">
            <div class="menu-item">
                <button type="button" class="menu-link w-100 text-danger border-0 bg-transparent"
                    onclick="confirmLogout()">
                    <i class="fas fa-power-off"></i>
                    <span class="menu-text">Keluar</span>
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </aside>

    <main id="content">
        <header class="topbar">
            <button class="toggle-btn" id="toggleSidebar"><i class="fas fa-bars-staggered"></i></button>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <p class="mb-0 fw-bold small text-dark">{{ auth()->user()->username }}</p>
                    <p class="mb-0 text-muted small">{{ auth()->user()->getRoleNames()->first() }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->username }}&background=0d9488&color=fff"
                    class="rounded-3" width="40" height="40">
            </div>
        </header>

        <div class="main-container">
            @yield('content')
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('toggleSidebar');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    const sidebar = document.getElementById('sidebar');
                    const content = document.getElementById('content');
                    if (sidebar.classList.contains('collapsed')) {
                        document.querySelectorAll('.menu-item.open').forEach(item => {
                            item.classList.remove('open');
                            const submenu = item.querySelector('.submenu');
                            if (submenu) submenu.style.display = 'none';
                        });
                    }
                    sidebar.classList.toggle('collapsed');
                    content.classList.toggle('expanded');
                });
            }
        });

        window.toggleSubmenu = function (el) {
            const parent = el.parentElement;
            parent.classList.toggle('open');
            const submenu = parent.querySelector('.submenu');
            if (submenu) {
                submenu.style.display = parent.classList.contains('open') ? 'block' : 'none';
            }
        };

        window.confirmDelete = function (url) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                border: 'none',
                borderRadius: '16px'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = '@csrf @method("DELETE")';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        };

        window.confirmLogout = function () {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: "Apakah Anda yakin ingin mengakhiri sesi?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        };
    </script>
    @stack('scripts')
</body>

</html>
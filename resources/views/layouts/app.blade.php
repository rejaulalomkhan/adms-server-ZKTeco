<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMS Server</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --sidebar-collapsed-width: 72px; }
        body { min-height: 100vh; }
        .layout { display: flex; min-height: 100vh; }
        .sidebar {
            width: var(--sidebar-width);
            background: #0b2447;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin; /* Firefox */
            scrollbar-color: rgba(255,255,255,.3) transparent; /* Firefox */
        }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.3); border-radius: 4px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.5); }
        .sidebar .brand { display:flex; align-items:center; gap:10px; padding:16px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar .brand img { width: 28px; height: 28px; }
        .sidebar .brand span { font-weight: 600; font-size: 16px; }
        
        /* Collapsed state */
        body.sidebar-collapsed .sidebar { width: var(--sidebar-collapsed-width); }
        body.sidebar-collapsed .content-wrap { margin-left: var(--sidebar-collapsed-width); }
        body.sidebar-collapsed .sidebar .brand span,
        body.sidebar-collapsed .sidebar .nav-group,
        body.sidebar-collapsed .sidebar .nav-text { display: none; }
        body.sidebar-collapsed .sidebar .nav-link { justify-content: center; }
        .sidebar .nav-link { color: #cbd5e1; border-radius: 8px; display:flex; align-items:center; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color:#fff; background: rgba(255,255,255,.08); }
        .sidebar .nav-group { padding: 8px 16px; text-transform: uppercase; font-size: 11px; color: #94a3b8; }
        .sidebar .nav-link i { width: 20px; text-align:center; }
        .sidebar .nav-text { margin-left: 8px; }
        .content-wrap { flex: 1; display:flex; flex-direction:column; background:#f5f7fb; margin-left: var(--sidebar-width); min-height: 100vh; transition: margin-left .2s ease; }
        .topbar { height: 56px; background:#ffffff; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; padding:0 16px; position:sticky; top:0; z-index: 1020; }
        .topbar .user { display:flex; align-items:center; gap:10px; }
        .main { padding: 16px; }
        .sidebar-toggle { display:inline-flex; }
        .dev-credit { position: fixed; right: 12px; bottom: 12px; z-index: 1050; font-size: 12px; color: #64748b; }
        .dev-credit a { text-decoration: none; color: inherit; background: rgba(255,255,255,.9); border: 1px solid #e5e7eb; padding: 6px 10px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,.05); }
        .dev-credit a:hover { background: #ffffff; color: #0b2447; }
        .dev-credit .dev-name { font-weight: 700; color: #16a34a; }
        @media (max-width: 991.98px) {
            .sidebar { position: fixed; left: -100%; transition: left .3s ease; z-index: 1030; }
            body.sidebar-open .sidebar { left: 0; }
            .sidebar-toggle { display:inline-flex; }
            .content-wrap { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand animate__animated animate__fadeIn">
                <img src="{{ asset('img/finger-scan.svg') }}" alt="Logo">
                <span>Attandance Server</span>
            </div>
            <nav class="p-2">
                <div class="nav-group">Overview</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('dashboard.index') }}"><i class="fa-solid fa-gauge"></i><span class="nav-text"> Dashboard</span></a>
                <div class="nav-group mt-3">Devices</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('devices.index') }}"><i class="fa-solid fa-fingerprint"></i><span class="nav-text"> Devices</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('devices.Attendance') }}"><i class="fa-regular fa-clock"></i><span class="nav-text"> Attendance</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('devices.DeviceLog') }}"><i class="fa-regular fa-file-lines"></i><span class="nav-text"> Device Log</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('devices.FingerLog') }}"><i class="fa-regular fa-hand-point-up"></i><span class="nav-text"> Finger Log</span></a>
                <div class="nav-group mt-3">Scheduling</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('shifts.index') }}"><i class="fa-solid fa-table-columns"></i><span class="nav-text"> Shifts</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('shift-rotations.index') }}"><i class="fa-solid fa-rotate"></i><span class="nav-text"> Shift Rotations</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('shift-assignments.index') }}"><i class="fa-solid fa-user-clock"></i><span class="nav-text"> Shift Assignments</span></a>
                <div class="nav-group mt-3">HR & Policy</div>
                @role('Super Admin')
                <a class="nav-link px-3 py-2 d-block" href="{{ route('users.index') }}"><i class="fa-solid fa-id-card"></i><span class="nav-text"> Users</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('roles.index') }}"><i class="fa-solid fa-user-shield"></i><span class="nav-text"> Roles</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('permissions.index') }}"><i class="fa-solid fa-key"></i><span class="nav-text"> Permissions</span></a>
                @endrole
                <a class="nav-link px-3 py-2 d-block" href="{{ route('holidays.index') }}"><i class="fa-regular fa-calendar-days"></i><span class="nav-text"> Holidays</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('overtime.index') }}"><i class="fa-solid fa-hourglass-half"></i><span class="nav-text"> Overtime</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('reports.index') }}"><i class="fa-solid fa-file-export"></i><span class="nav-text"> Reports</span></a>
                <div class="nav-group mt-3">Organization</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('offices.index') }}"><i class="fa-solid fa-building"></i><span class="nav-text"> Offices</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('user-offices.index') }}"><i class="fa-solid fa-users"></i><span class="nav-text"> User Offices</span></a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('areas.index') }}"><i class="fa-solid fa-map-location"></i><span class="nav-text"> Areas</span></a>
            </nav>
        </aside>
        <div class="content-wrap">
            <header class="topbar">
                <button class="btn btn-light sidebar-toggle" id="sidebarToggle"><i id="sidebarToggleIcon" class="fa-solid fa-bars"></i></button>
                <div class="ms-auto">
                    @auth
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name={{ urlencode(Auth::user()->name ?? 'Guest') }}" alt="avatar" class="rounded-circle" width="28" height="28">
                            <span class="ms-2">{{ Auth::user()->name ?? 'Guest' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
            </header>
            <main class="main">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <div class="dev-credit">
        <a href="https://fb.com/armanaazij" target="_blank" rel="noopener">Developer: <span class="dev-name">Arman azij</span></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            // On desktop toggle collapse; on mobile toggle drawer
            if (window.matchMedia('(max-width: 991.98px)').matches) {
                document.body.classList.toggle('sidebar-open');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
            }
            const icon = document.getElementById('sidebarToggleIcon');
            if (document.body.classList.contains('sidebar-collapsed') || document.body.classList.contains('sidebar-open')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        });
    </script>
</body>
</html>
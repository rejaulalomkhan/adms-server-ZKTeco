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
        :root { --sidebar-width: 260px; }
        body { min-height: 100vh; }
        .layout { display: flex; min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); background: #0b2447; color: #fff; position: sticky; top: 0; height: 100vh; }
        .sidebar .brand { display:flex; align-items:center; gap:10px; padding:16px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar .brand img { width: 28px; height: 28px; }
        .sidebar .brand span { font-weight: 600; font-size: 16px; }
        .sidebar .nav-link { color: #cbd5e1; border-radius: 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color:#fff; background: rgba(255,255,255,.08); }
        .sidebar .nav-group { padding: 8px 16px; text-transform: uppercase; font-size: 11px; color: #94a3b8; }
        .content-wrap { flex: 1; display:flex; flex-direction:column; background:#f5f7fb; }
        .topbar { height: 56px; background:#ffffff; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; padding:0 16px; position:sticky; top:0; z-index: 1020; }
        .topbar .user { display:flex; align-items:center; gap:10px; }
        .main { padding: 16px; }
        .sidebar-toggle { display:none; }
        .dev-credit { position: fixed; right: 12px; bottom: 12px; z-index: 1050; font-size: 12px; color: #64748b; }
        .dev-credit a { text-decoration: none; color: inherit; background: rgba(255,255,255,.9); border: 1px solid #e5e7eb; padding: 6px 10px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,.05); }
        .dev-credit a:hover { background: #ffffff; color: #0b2447; }
        .dev-credit .dev-name { font-weight: 700; color: #16a34a; }
        @media (max-width: 991.98px) {
            .sidebar { position: fixed; left: -100%; transition: left .3s ease; z-index: 1030; }
            body.sidebar-open .sidebar { left: 0; }
            .sidebar-toggle { display:inline-flex; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand animate__animated animate__fadeIn">
                <img src="/favicon.ico" alt="Logo">
                <span>ADMS Server</span>
            </div>
            <nav class="p-2">
                <div class="nav-group">Overview</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('dashboard.index') }}"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
                <div class="nav-group mt-3">Devices</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('devices.index') }}"><i class="fa-solid fa-fingerprint me-2"></i> Devices</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('devices.Attendance') }}"><i class="fa-regular fa-clock me-2"></i> Attendance</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('devices.DeviceLog') }}"><i class="fa-regular fa-file-lines me-2"></i> Device Log</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('devices.FingerLog') }}"><i class="fa-regular fa-hand-point-up me-2"></i> Finger Log</a>
                <div class="nav-group mt-3">Scheduling</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('shifts.index') }}"><i class="fa-solid fa-table-columns me-2"></i> Shifts</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('shift-rotations.index') }}"><i class="fa-solid fa-rotate me-2"></i> Shift Rotations</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('shift-assignments.index') }}"><i class="fa-solid fa-user-clock me-2"></i> Shift Assignments</a>
                <div class="nav-group mt-3">HR & Policy</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('users.index') }}"><i class="fa-solid fa-id-card me-2"></i> Employees</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('holidays.index') }}"><i class="fa-regular fa-calendar-days me-2"></i> Holidays</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('overtime.index') }}"><i class="fa-solid fa-hourglass-half me-2"></i> Overtime</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('reports.index') }}"><i class="fa-solid fa-file-export me-2"></i> Reports</a>
                <div class="nav-group mt-3">Organization</div>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('offices.index') }}"><i class="fa-solid fa-building me-2"></i> Offices</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('user-offices.index') }}"><i class="fa-solid fa-users me-2"></i> User Offices</a>
                <a class="nav-link px-3 py-2 d-block" href="{{ route('areas.index') }}"><i class="fa-solid fa-map-location me-2"></i> Areas</a>
            </nav>
        </aside>
        <div class="content-wrap">
            <header class="topbar">
                <button class="btn btn-light sidebar-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <div class="ms-auto dropdown">
                    <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name={{ urlencode(Auth::user()->name ?? 'Guest') }}" alt="avatar" class="rounded-circle" width="28" height="28">
                        <span class="ms-2">{{ Auth::user()->name ?? 'Guest' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="#">Edit Profile</a></li>
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
            document.body.classList.toggle('sidebar-open');
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Admin SMPI Baabussalaam</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-smp.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2a9d8f;
            --primary-dark: #21867a;
            --primary-light: rgba(42, 157, 143, 0.1);
            --secondary: #264653;
            --secondary-light: rgba(38, 70, 83, 0.9);
            --accent: #e9c46a;
            --accent-dark: #d9b459;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-width: 250px;
            --sidebar-mini-width: 70px;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
            transition: margin-left var(--transition-speed);
        }

        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(to bottom, var(--secondary), var(--secondary-light));
            color: white;
            transition: all var(--transition-speed);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        #sidebar.mini {
            width: var(--sidebar-mini-width);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .sidebar-header {
            padding: 20px 15px;
            background: var(--primary);
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: padding var(--transition-speed);
            position: relative;
        }

        #sidebar.mini .sidebar-header {
            padding: 20px 10px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            transition: all var(--transition-speed);
        }

        .logo {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all var(--transition-speed);
        }

        .logo i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            transition: opacity var(--transition-speed);
        }

        .logo-text h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
        }

        .logo-text p {
            margin: 2px 0 0;
            font-size: 0.75rem;
            opacity: 0.8;
            line-height: 1.2;
        }

        #sidebar.mini .logo-container {
            justify-content: center;
            margin-bottom: 0;
        }

        #sidebar.mini .logo {
            margin-right: 0;
        }

        #sidebar.mini .logo-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
        }

        #sidebar ul.components {
            padding: 20px 0;
            margin: 0;
        }

        #sidebar ul li {
            margin: 5px 10px;
            position: relative;
        }

        #sidebar ul li a {
            padding: 12px 15px;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all var(--transition-speed);
            border-radius: 6px;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        #sidebar ul li a:hover {
            color: white;
            background: var(--primary-light);
            transform: translateX(5px);
        }

        #sidebar ul li.active>a {
            color: white;
            background: var(--primary);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #sidebar ul li a i,
        #sidebar ul li a .material-icons {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            transition: margin var(--transition-speed);
        }

        #sidebar.mini ul li a i,
        #sidebar.mini ul li a .material-icons {
            margin-right: 0;
        }

        .menu-text {
            transition: opacity var(--transition-speed);
        }

        #sidebar.mini .menu-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .sidebar-toggle {
            width: 100%;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 6px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background var(--transition-speed);
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-toggle i {
            margin-right: 8px;
            transition: transform var(--transition-speed);
        }

        #sidebar.mini .sidebar-toggle i {
            transform: rotate(180deg);
            margin-right: 0;
        }

        .sidebar-toggle-text {
            transition: opacity var(--transition-speed);
        }

        #sidebar.mini .sidebar-toggle-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        #sidebar.mini ul li a::after {
            content: attr(data-title);
            position: absolute;
            left: calc(var(--sidebar-mini-width) + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--secondary);
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-speed);
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #sidebar.mini ul li a:hover::after {
            opacity: 1;
            visibility: visible;
            left: calc(var(--sidebar-mini-width) + 15px);
        }

        #content {
            margin-left: var(--sidebar-width);
            transition: all var(--transition-speed);
            min-height: 100vh;
            padding-top: 56px;
        }

        #content.mini {
            margin-left: var(--sidebar-mini-width);
        }

        .navbar-top {
            height: 56px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 999;
            transition: all var(--transition-speed);
            padding: 0.5rem 1rem;
        }

        .navbar-top.mini {
            left: var(--sidebar-mini-width);
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -var(--sidebar-width);
                width: var(--sidebar-width);
                z-index: 1000;
            }

            #sidebar.mini {
                margin-left: -var(--sidebar-mini-width);
                width: var(--sidebar-mini-width);
            }

            #sidebar.active {
                margin-left: 0;
                box-shadow: 3px 0 15px rgba(0, 0, 0, 0.3);
            }

            #content {
                margin-left: 0;
                width: 100%;
            }

            #content.mini {
                margin-left: 0;
            }

            .navbar-top {
                left: 0;
                width: 100%;
            }

            .navbar-top.mini {
                left: 0;
            }

            #content.active {
                margin-left: var(--sidebar-width);
            }

            #content.mini.active {
                margin-left: var(--sidebar-mini-width);
            }

            .navbar-top.active {
                left: var(--sidebar-width);
            }

            .navbar-top.mini.active {
                left: var(--sidebar-mini-width);
            }

            #sidebar.mini ul li a::after {
                display: none;
            }

            .sidebar-footer {
                display: none;
            }

            .container-fluid {
                padding: 0 15px;
            }
        }

        .navbar-top .navbar-toggler {
            color: var(--secondary);
        }

        .user-dropdown .dropdown-toggle {
            color: var(--dark);
            text-decoration: none;
        }

        .user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 15px 20px;
        }

        .card-body {
            padding: 20px;
        }

        .stat-card {
            text-align: center;
            padding: 20px;
        }

        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .stat-card .stat-number {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--secondary);
            background: #f8f9fa;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .badge-primary {
            background-color: var(--primary);
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 2rem;
            margin: 0 auto 20px;
        }

        .profile-info-item {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .profile-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .profile-info-content {
            flex-grow: 1;
        }

        .profile-info-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .profile-info-value {
            font-weight: 500;
            color: var(--dark);
        }

        .form-label {
            font-weight: 500;
            color: var(--secondary);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(42, 157, 143, 0.25);
        }

        .nav-tabs .nav-link {
            color: var(--secondary);
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-color: var(--primary) var(--primary) #fff;
        }

        .swal2-container.swal2-top-end {
            padding: 10px;
        }

        .swal2-popup.swal2-toast {
            padding: 10px 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @yield('styles')
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav id="sidebar" class="active">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-school"></i>
                </div>
                <div class="logo-text">
                    <h4 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Administrator</h4>
                    <p>Panel Administrasi</p>
                </div>
            </div>
        </div>

        <div class="sidebar-content">
            <ul class="list-unstyled components">
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" data-title="Dashboard">
                        <span class="material-icons mr-3">dashboard</span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/berita*') ? 'active' : '' }}">
                    <a href="{{ route('admin.berita.index') }}" data-title="Kelola Berita">
                        <span class="material-icons mr-3">article</span>
                        <span class="menu-text">Kelola Berita</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/guru*') ? 'active' : '' }}">
                    <a href="{{ route('admin.guru.index') }}" data-title="Guru & Staff">
                        <span class="material-icons mr-3">group</span>
                        <span class="menu-text">Guru & Staff</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/fasilitas*') ? 'active' : '' }}">
                    <a href="{{ route('admin.fasilitas.index') }}" data-title="Fasilitas">
                        <span class="material-icons mr-3">apartment</span>
                        <span class="menu-text">Fasilitas</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/galeri*') ? 'active' : '' }}">
                    <a href="{{ route('admin.galeri.index') }}" data-title="Galeri">
                        <span class="material-icons mr-3">collections</span>
                        <span class="menu-text">Galeri</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/halaman*') ? 'active' : '' }}">
                    <a href="{{ route('admin.halaman.index') }}" data-title="Halaman">
                        <span class="material-icons mr-3">pages</span>
                        <span class="menu-text">Halaman</span>
                    </a>
                </li>
                @if(Auth::user()->role == 'admin')
                <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" data-title="Pengguna">
                        <span class="material-icons mr-3">person</span>
                        <span class="menu-text">Pengguna</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ url('/') }}" target="_blank" data-title="Lihat Website">
                        <span class="material-icons mr-3">visibility</span>
                        <span class="menu-text">Lihat Website</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 12px 15px; display: flex; align-items: center; color: rgba(255, 255, 255, 0.8); cursor: pointer; border-radius: 6px; font-size: 0.95rem;" data-title="Logout" onmouseover="this.style.color='white'; this.style.background='var(--primary-light)'; this.style.transform='translateX(5px)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'; this.style.background='none'; this.style.transform='translateX(0)'">
                            <span class="material-icons mr-3">logout</span>
                            <span class="menu-text">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <button class="sidebar-toggle">
                <i class="fas fa-chevron-left"></i>
                <span class="sidebar-toggle-text">Sembunyikan Sidebar</span>
            </button>
        </div>
    </nav>

    <div id="content" class="active">
        <nav class="navbar navbar-expand navbar-top active">
            <div class="container-fluid">
                <button id="sidebarToggle" class="btn btn-link">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2">
                                    {{ strtoupper(substr(Auth::user()->fullname, 0, 1)) }}
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->fullname }}</span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="cursor: pointer; background: none; border: none; width: 100%; text-align: left;">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid mt-2">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">@yield('breadcrumb', 'Admin')</li>
                    </ol>
                </nav>
            </div>

            @yield('content')

        </div>
    </div>

    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Profil Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(Auth::user()->fullname, 0, 1)) }}
                        </div>
                        <h4>{{ Auth::user()->fullname }}</h4>
                        <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="profile-info-content">
                                    <div class="profile-info-label">Nama Lengkap</div>
                                    <div class="profile-info-value">{{ Auth::user()->fullname }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="profile-info-content">
                                    <div class="profile-info-label">Email</div>
                                    <div class="profile-info-value">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-icon">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <div class="profile-info-content">
                                    <div class="profile-info-label">Role</div>
                                    <div class="profile-info-value">{{ ucfirst(Auth::user()->role) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="profile-info-content">
                                    <div class="profile-info-label">Terdaftar Pada</div>
                                    <div class="profile-info-value">{{ Auth::user()->created_at->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="profile-info-content">
                                    <div class="profile-info-label">Terakhir Diperbarui</div>
                                    <div class="profile-info-value">{{ Auth::user()->updated_at->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="profile-info-content">
                                    <div class="profile-info-label">Status Akun</div>
                                    <div class="profile-info-value">
                                        <span class="badge bg-success">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#settingsModal" data-bs-dismiss="modal">Edit Profil</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">Pengaturan Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.dashboard') }}">
                    @csrf
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Edit Profil</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">Ubah Password</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="settingsTabsContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" value="{{ Auth::user()->fullname }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                                </div>
                                <input type="hidden" name="update_profile" value="1">
                            </div>

                            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                    <div class="form-text">Minimal 6 karakter</div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                                <input type="hidden" name="update_password" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const navbarTop = document.querySelector('.navbar-top');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const mobileSidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            const isMobile = window.innerWidth <= 768;

            if (isMobile) {
                sidebar.classList.remove('active');
                content.classList.remove('active');
                navbarTop.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            } else {
                sidebar.classList.add('active');
                content.classList.add('active');
                navbarTop.classList.add('active');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (window.innerWidth > 768) {
                        sidebar.classList.toggle('mini');
                        content.classList.toggle('mini');
                        navbarTop.classList.toggle('mini');

                        const icon = this.querySelector('i');
                        const text = this.querySelector('.sidebar-toggle-text');
                        if (sidebar.classList.contains('mini')) {
                            icon.classList.remove('fa-chevron-left');
                            icon.classList.add('fa-chevron-right');
                            text.textContent = 'Tampilkan Sidebar';
                        } else {
                            icon.classList.remove('fa-chevron-right');
                            icon.classList.add('fa-chevron-left');
                            text.textContent = 'Sembunyikan Sidebar';
                        }

                        if (sidebar.classList.contains('mini')) {
                            localStorage.setItem('sidebarState', 'mini');
                        } else {
                            localStorage.setItem('sidebarState', 'normal');
                        }
                    }
                });
            }

            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    content.classList.toggle('active');
                    navbarTop.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');

                    if (sidebar.classList.contains('active')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = 'auto';
                    }
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    content.classList.remove('active');
                    navbarTop.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            }

            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickInsideToggle = mobileSidebarToggle && mobileSidebarToggle.contains(event.target);
                    const isClickInsideOverlay = sidebarOverlay && sidebarOverlay.contains(event.target);

                    if (!isClickInsideSidebar && !isClickInsideToggle && !isClickInsideOverlay && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        content.classList.remove('active');
                        navbarTop.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        document.body.style.overflow = 'auto';
                    }
                }
            });

            if (window.innerWidth > 768) {
                const sidebarState = localStorage.getItem('sidebarState');
                if (sidebarState === 'mini') {
                    sidebar.classList.add('mini');
                    content.classList.add('mini');
                    navbarTop.classList.add('mini');
                    if (sidebarToggle) {
                        const icon = sidebarToggle.querySelector('i');
                        const text = sidebarToggle.querySelector('.sidebar-toggle-text');
                        icon.classList.remove('fa-chevron-left');
                        icon.classList.add('fa-chevron-right');
                        text.textContent = 'Tampilkan Sidebar';
                    }
                }
            }

            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');

            if (newPassword && confirmPassword) {
                confirmPassword.addEventListener('input', function() {
                    if (newPassword.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity('Konfirmasi password tidak sesuai');
                    } else {
                        confirmPassword.setCustomValidity('');
                    }
                });
            }

            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.add('active');
                    content.classList.add('active');
                    navbarTop.classList.add('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';

                    const sidebarState = localStorage.getItem('sidebarState');
                    if (sidebarState === 'mini') {
                        sidebar.classList.add('mini');
                        content.classList.add('mini');
                        navbarTop.classList.add('mini');
                        if (sidebarToggle) {
                            const icon = sidebarToggle.querySelector('i');
                            const text = sidebarToggle.querySelector('.sidebar-toggle-text');
                            icon.classList.remove('fa-chevron-left');
                            icon.classList.add('fa-chevron-right');
                            text.textContent = 'Tampilkan Sidebar';
                        }
                    } else {
                        sidebar.classList.remove('mini');
                        content.classList.remove('mini');
                        navbarTop.classList.remove('mini');
                        if (sidebarToggle) {
                            const icon = sidebarToggle.querySelector('i');
                            const text = sidebarToggle.querySelector('.sidebar-toggle-text');
                            icon.classList.remove('fa-chevron-right');
                            icon.classList.add('fa-chevron-left');
                            text.textContent = 'Sembunyikan Sidebar';
                        }
                    }
                } else {
                    sidebar.classList.remove('active');
                    content.classList.remove('active');
                    navbarTop.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
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

            Toast.fire({
                icon: 'success',
                title: '{{ session("success") }}'
            });
        });
        @endif

        @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
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

            Toast.fire({
                icon: 'error',
                title: '{{ session("error") }}'
            });
        });
        @endif
    </script>

    @yield('scripts')

</body>

</html>
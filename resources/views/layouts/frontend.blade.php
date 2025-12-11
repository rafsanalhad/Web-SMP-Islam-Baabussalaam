<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMP Islam Baabussalaam')</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-smp.ico') }}" type="image/x-icon">
    
    @yield('styles')
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar bg-dark text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="me-3">
                            <i class="fas fa-phone me-2"></i> (021) 12345678
                        </div>
                        <div>
                            <i class="fas fa-envelope me-2"></i> info@smpbaabussalaam.sch.id
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Header -->
    <header class="main-header bg-white py-3 shadow-sm">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-3">
                    <div class="logo-container">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/img/logo-smp.png') }}" alt="Logo SMP Baabussalaam" class="img-fluid" style="height: 70px;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-10 col-md-9 mt-3 mt-md-0">
                    <div class="school-info">
                        <h1 class="school-name mb-2 fw-bold text-secondary" style="font-size: 1.8rem;">SMP Islam Baabussalaam</h1>
                        <p class="school-address mb-0 text-muted">
                            <i class="fas fa-map-marker-alt me-2"></i>Jl. Abadi Dusun Kuwut RT. 01 RW. 06 Nglegok, Kab.Blitar - NPSN: 70054377
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #198754 0%, #0d6e3f 100%);">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle me-1"></i> Profil
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('/tentang') }}"><i class="fas fa-history me-2"></i>Sejarah</a></li>
                            <li><a class="dropdown-item" href="{{ url('/tentang') }}#visi-misi"><i class="fas fa-bullseye me-2"></i>Visi Misi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ url('/tentang') }}#prestasi"><i class="fas fa-chart-line me-2"></i>Prestasi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('akademik') ? 'active' : '' }}" href="{{ url('/akademik') }}">
                            <i class="fas fa-book me-1"></i> Akademik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('guru-staff') ? 'active' : '' }}" href="{{ url('/guru-staff') }}">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Guru & Staff
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('fasilitas') ? 'active' : '' }}" href="{{ url('/fasilitas') }}">
                            <i class="fas fa-building me-1"></i> Fasilitas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('galeri') ? 'active' : '' }}" href="{{ url('/galeri') }}">
                            <i class="fas fa-images me-1"></i> Galeri
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <a href="#" class="btn btn-outline-light btn-sm ms-2">
                        <i class="fas fa-envelope me-1"></i> Kontak Kami
                    </a>
                    <a href="#" class="btn btn-light btn-sm ms-2">
                        <i class="fas fa-user-graduate me-1"></i> Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>Tentang Kami</h5>
                    <p>SMP Baabussalaam adalah sekolah unggulan berbasis Aswaja di Blitar.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Abadi Dusun Kuwut RT. 01 RW. 06</li>
                        <li><i class="fas fa-phone me-2"></i> 085649305903 </li>
                        <li><i class="fas fa-envelope me-2"></i> smpibaabussalaam@gmail.com</li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/akademik') }}" class="text-white">Kalender Akademik</a></li>
                        <li><a href="{{ url('/guru-staff') }}" class="text-white">Daftar Guru</a></li>
                        <li><a href="{{ url('/fasilitas') }}" class="text-white">Fasilitas Sekolah</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} SMP Baabussalaam. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @yield('scripts')
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMP Islam Baabussalaam - Beranda</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Modern Color Scheme */
        :root {
            --primary: #2a9d8f;
            --primary-dark: #21867a;
            --secondary: #264653;
            --accent: #e9c46a;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Modern Typography */
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            line-height: 1.2;
        }
        
        /* Section Styling */
        section {
            padding: 5rem 0;
            position: relative;
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            bottom: -10px;
            left: 0;
            border-radius: 2px;
        }
        
        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .scale-in {
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .scale-in.visible {
            transform: scale(1);
            opacity: 1;
        }
        
        .delay-1 { transition-delay: 0.2s; }
        .delay-2 { transition-delay: 0.4s; }
        .delay-3 { transition-delay: 0.6s; }
        
        /* Card Hover Effects */
        .hover-effect {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }
        
        .hover-effect:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12) !important;
        }
        
        .hover-effect:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(42,157,143,0.1), rgba(233,196,106,0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .hover-effect:hover:before {
            opacity: 1;
        }
        
        /* Buttons */
        .btn {
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-outline-light:hover {
            color: var(--primary) !important;
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            background: linear-gradient(135deg, rgba(38,70,83,0.9), rgba(42,157,143,0.9)), url('{{ asset('assets/img/hero-bg.jpg') }}') no-repeat center center;
            background-size: cover;
            padding: 8rem 0;
            color: white;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
        }
        
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        
        /* Quick Info */
        .quick-info {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            padding: 2.5rem 0;
            margin-top: -5rem;
            position: relative;
            z-index: 1;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            color: white;
        }
        
        .quick-info i {
            background: rgba(255,255,255,0.15);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
        }
        
        /* Icon Box */
        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.25rem;
        }
        
        /* Facility Cards */
        .facility-card {
            transition: all 0.4s ease;
            border: none;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }
        
        .facility-card i {
            margin-bottom: 1.5rem;
            color: var(--primary);
        }
        
        /* News Cards */
        .news-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .news-card img {
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .news-card:hover img {
            transform: scale(1.05);
        }
        
        .news-card .card-body {
            flex: 1;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-section {
                clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
                padding: 5rem 0;
            }
            
            .quick-info {
                margin-top: 0;
            }
            
            .quick-info .col-md-3 {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>

<body>
    @include('components.preloader')

    <!-- Top Bar -->
    <div class="top-bar text-white py-2" style="background: linear-gradient(135deg, var(--secondary), var(--primary-dark));">
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
    <header class="bg-white py-3 shadow-sm">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-3 col-4">
                    <div class="logo-container">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/img/logo-smp.png') }}" alt="Logo SMP Baabussalaam" class="img-fluid" style="height: 70px;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-10 col-md-9 col-8 mt-3 mt-md-0">
                    <div class="school-info">
                        <h1 class="fw-bold mb-2" style="font-size: 1.5rem; color: var(--secondary);">SMP Islam Baabussalaam</h1>
                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                            <i class="fas fa-map-marker-alt me-2"></i>Jl. Abadi Dusun Kuwut RT. 01 RW. 06 Nglegok, Kab.Blitar - NPSN: 70054377
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: linear-gradient(135deg, #198754 0%, #0d6e3f 100%); box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/') }}">
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
                        <a class="nav-link" href="{{ url('/akademik') }}">
                            <i class="fas fa-book me-1"></i> Akademik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/guru-staff') }}">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Guru & Staff
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/fasilitas') }}">
                            <i class="fas fa-building me-1"></i> Fasilitas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/galeri') }}">
                            <i class="fas fa-images me-1"></i> Galeri
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <a href="{{ url('/kontak') }}" class="btn btn-outline-light btn-sm rounded-pill ms-2">
                        <i class="fas fa-envelope me-1"></i> Kontak Kami
                    </a>
                    <a href="#" class="btn btn-light btn-sm rounded-pill ms-2">
                        <i class="fas fa-user-graduate me-1"></i> Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <style>
        .navbar .nav-link {
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar .nav-link:hover {
            background-color: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }
        
        .navbar .nav-link.active {
            font-weight: 600;
            background-color: rgba(255,255,255,0.1);
        }
        
        .navbar .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 5px;
        }
        
        .navbar .dropdown-item {
            padding: 8px 15px;
            border-radius: 4px;
            margin: 2px 5px;
            transition: all 0.3s ease;
        }
        
        .navbar .dropdown-item:hover {
            background: linear-gradient(135deg, #198754 0%, #0d6e3f 100%);
            color: white;
            transform: translateX(5px);
        }
        
        @media (max-width: 991.98px) {
            .navbar-collapse {
                padding: 15px 0;
                margin-top: 10px;
                border-radius: 8px;
            }
        }
    </style>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 text-white">
                    <h1 class="display-4 fw-bold mb-4 fade-in">Selamat Datang di SMP Islam Baabussalaam</h1>
                    <p class="lead mb-4 fade-in delay-1">Membentuk generasi unggul dengan integritas, intelektualitas, dan spiritualitas</p>
                    <div class="d-flex flex-wrap gap-3 fade-in delay-2">
                        <a href="{{ url('/tentang') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-info-circle me-2"></i> Tentang Kami
                        </a>
                        <a href="{{ url('/galeri') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-images me-2"></i> Galeri Sekolah
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block fade-in delay-3">
                    <div class="card border-0 bg-white bg-opacity-10 text-white p-4 hover-effect" style="backdrop-filter: blur(10px);">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Penerimaan Siswa Baru 2023/2024</h4>
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-calendar-alt me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Periode Pendaftaran</h6>
                                        <small>1 Juni - 15 Juli 2023</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-graduate me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Kuota Penerimaan</h6>
                                        <small>120 Siswa (4 Kelas)</small>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-accent w-100 py-2" style="background-color: var(--accent); color: var(--secondary);">
                                <i class="fas fa-file-alt me-2"></i> Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Info -->
    <section class="container">
        <div class="quick-info">
            <div class="row text-center">
                <div class="col-md-3 mb-3 mb-md-0 fade-in">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-users"></i>
                        <div class="text-start">
                            <h3 class="mb-0 fw-bold">1,200+</h3>
                            <small class="text-uppercase">Siswa</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0 fade-in delay-1">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <div class="text-start">
                            <h3 class="mb-0 fw-bold">45+</h3>
                            <small class="text-uppercase">Guru</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0 fade-in delay-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-building"></i>
                        <div class="text-start">
                            <h3 class="mb-0 fw-bold">18</h3>
                            <small class="text-uppercase">Kelas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 fade-in delay-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-trophy"></i>
                        <div class="text-start">
                            <h3 class="mb-0 fw-bold">32</h3>
                            <small class="text-uppercase">Prestasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 scale-in">
                    <div class="position-relative">
                        <img src="{{ asset('assets/img/about.png') }}" alt="Tentang SMP Baabussalaam" class="img-fluid rounded-3 shadow-lg" style="z-index: 1; position: relative;" onerror="this.src='https://via.placeholder.com/600x400?text=Tentang+Kami'">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary rounded-3" style="z-index: 0; transform: translate(-15px, 15px); opacity: 0.1;"></div>
                    </div>
                </div>
                <div class="col-lg-6 scale-in delay-1">
                    <h2 class="section-title fw-bold">Sekolah Unggulan Berbasis Karakter</h2>
                    <p class="lead mb-4">Membentuk generasi unggul dengan dasar akhlak mulia dan penguasaan ilmu pengetahuan yang seimbang.</p>
                    
                    <div class="mb-4">
                        <div class="d-flex mb-3 fade-in delay-1">
                            <div class="icon-box bg-primary text-white me-4">
                                <i class="fas fa-award"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold">Akreditasi A</h5>
                                <p class="mb-0 text-muted">Terakreditasi dengan predikat sangat baik oleh BAN-S/M</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3 fade-in delay-2">
                            <div class="icon-box bg-success text-white me-4">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold">Kurikulum Modern</h5>
                                <p class="mb-0 text-muted">Kurikulum terintegrasi dengan nilai-nilai Islami dan teknologi</p>
                            </div>
                        </div>
                        <div class="d-flex fade-in delay-3">
                            <div class="icon-box bg-warning text-white me-4">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold">Guru Berkompeten</h5>
                                <p class="mb-0 text-muted">Diajar oleh guru-guru profesional dan bersertifikasi</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ url('/tentang') }}" class="btn btn-primary fade-in delay-3">
                            <i class="fas fa-book-reader me-2"></i> Profil Sekolah
                        </a>
                        <a href="{{ url('/guru-staff') }}" class="btn btn-outline-primary fade-in delay-3">
                            <i class="fas fa-users me-2"></i> Kenali Guru Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5 fade-in">
                <h2 class="section-title fw-bold">Berita Terkini</h2>
                <a href="{{ url('/berita') }}" class="btn btn-outline-primary">
                    <i class="fas fa-newspaper me-2"></i> Lihat Semua
                </a>
            </div>
            
            <div class="row g-4">
                @forelse($latestNews->take(3) as $index => $news)
                <div class="col-md-4 fade-in @if($index == 1) delay-1 @elseif($index == 2) delay-2 @endif">
                    <div class="card news-card h-100 border-0 shadow-sm hover-effect">
                        @if($news->image)
                            <img src="{{ asset('assets/img/news/' . $news->image) }}" class="card-img-top" alt="{{ $news->title }}" onerror="this.src='{{ asset('assets/img/hero-bg.jpg') }}'">
                        @else
                            <img src="{{ asset('assets/img/hero-bg.jpg') }}" class="card-img-top" alt="{{ $news->title }}">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted"><i class="fas fa-calendar-alt me-2"></i>{{ $news->created_at->format('d M Y') }}</small>
                                <span class="badge 
                                    @if($news->category == 'prestasi') bg-primary
                                    @elseif($news->category == 'kegiatan') bg-success
                                    @elseif($news->category == 'pengumuman') bg-warning text-dark
                                    @else bg-info
                                    @endif
                                ">{{ ucfirst($news->category) }}</span>
                            </div>
                            <h5 class="card-title">{{ Str::limit($news->title, 60) }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($news->excerpt, 100) }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('news.detail', $news->id) }}" class="btn btn-sm btn-primary stretched-link">
                                <i class="fas fa-arrow-right me-1"></i> Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> Belum ada berita terbaru.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Facilities Preview -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5 fade-in">
                <h2 class="section-title fw-bold">Fasilitas Sekolah</h2>
                <a href="{{ url('/fasilitas') }}" class="btn btn-outline-primary">
                    <i class="fas fa-building me-2"></i> Lihat Semua
                </a>
            </div>
            
            <div class="row g-4">
                @forelse($facilities as $index => $facility)
                <div class="col-md-3 col-sm-6 fade-in @if($index == 1) delay-1 @elseif($index == 2) delay-2 @elseif($index == 3) delay-3 @endif">
                    <div class="facility-card text-center bg-white rounded-3 shadow-sm hover-effect">
                        @if($facility->image)
                            <img src="{{ asset('assets/img/facilities/' . $facility->image) }}" class="img-fluid rounded mb-3" style="height: 80px; width: 80px; object-fit: cover;" alt="{{ $facility->name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <i class="fas fa-building fa-3x mb-3" style="display: none; color: var(--primary);"></i>
                        @else
                            <i class="fas fa-building fa-3x mb-3" style="color: var(--primary);"></i>
                        @endif
                        <h5 class="fw-bold">{{ $facility->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($facility->description, 60) }}</p>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> Belum ada data fasilitas.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-5 bg-primary text-white" style="clip-path: polygon(0 10%, 100% 0, 100% 100%, 0 90%);">
        <div class="container">
            <div class="text-center mb-5 fade-in">
                <h2 class="section-title fw-bold">Apa Kata Mereka?</h2>
                <p class="lead">Testimoni dari orang tua siswa dan alumni</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4 fade-in">
                    <div class="card h-100 border-0 bg-white bg-opacity-10 hover-effect" style="backdrop-filter: blur(5px);">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-quote-left fa-2x opacity-25"></i>
                            </div>
                            <p class="card-text mb-4">"Sangat puas dengan perkembangan anak saya di Baabussalaam. Tidak hanya akademik, pembentukan karakternya juga sangat baik."</p>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/img/profil.jpg') }}" class="rounded-circle me-3" width="50" height="50" alt="Testimoni 1" onerror="this.src='https://via.placeholder.com/50?text=BF'">
                                <div>
                                    <h6 class="mb-0 fw-bold">Bpk. Ahmad Fauzi</h6>
                                    <small class="text-white-50">Orang Tua Siswa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 fade-in delay-1">
                    <div class="card h-100 border-0 bg-white bg-opacity-10 hover-effect" style="backdrop-filter: blur(5px);">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-quote-left fa-2x opacity-25"></i>
                            </div>
                            <p class="card-text mb-4">"Dasar-dasar keislaman yang kuat dari Baabussalaam sangat membantu saya di jenjang pendidikan lebih tinggi."</p>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/img/profil.jpg') }}" class="rounded-circle me-3" width="50" height="50" alt="Testimoni 2" onerror="this.src='https://via.placeholder.com/50?text=SA'">
                                <div>
                                    <h6 class="mb-0 fw-bold">Siti Aminah, S.Pd.</h6>
                                    <small class="text-white-50">Alumni 2015</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 fade-in delay-2">
                    <div class="card h-100 border-0 bg-white bg-opacity-10 hover-effect" style="backdrop-filter: blur(5px);">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-quote-left fa-2x opacity-25"></i>
                            </div>
                            <p class="card-text mb-4">"Fasilitas lengkap dan guru-guru yang kompeten membuat anak saya betah belajar di sekolah ini."</p>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/img/profil.jpg') }}" class="rounded-circle me-3" width="50" height="50" alt="Testimoni 3" onerror="this.src='https://via.placeholder.com/50?text=SW'">
                                <div>
                                    <h6 class="mb-0 fw-bold">Ibu Sarah Wijaya</h6>
                                    <small class="text-white-50">Orang Tua Siswa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Preview -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5 fade-in">
                <h2 class="section-title fw-bold">Galeri Kegiatan</h2>
                <a href="{{ url('/galeri') }}" class="btn btn-outline-primary">
                    <i class="fas fa-images me-2"></i> Lihat Semua
                </a>
            </div>
            
            <div class="row g-3">
                @forelse($gallery->take(4) as $index => $item)
                <div class="col-md-3 col-6 fade-in @if($index == 1) delay-1 @elseif($index == 2) delay-2 @elseif($index == 3) delay-3 @endif">
                    <a href="{{ url('/galeri') }}" class="gallery-item d-block overflow-hidden rounded-3 hover-effect position-relative">
                        @if($item->image)
                            <img src="{{ asset('assets/img/gallery/' . $item->image) }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="{{ $item->title }}" onerror="this.src='{{ asset('assets/img/galeri.jpg') }}'">
                        @else
                            <img src="{{ asset('assets/img/galeri.jpg') }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="{{ $item->title }}">
                        @endif
                        <div class="gallery-caption position-absolute bottom-0 w-100 bg-dark bg-opacity-50 text-white p-3">
                            <h6 class="mb-0">{{ Str::limit($item->title, 30) }}</h6>
                            @if($item->category)
                                <small class="text-white-50">{{ ucfirst($item->category) }}</small>
                            @endif
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> Belum ada galeri kegiatan.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-secondary text-white" style="clip-path: polygon(0 10%, 100% 0, 100% 100%, 0 90%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0 fade-in">
                    <h2 class="fw-bold mb-3">Tertarik Bergabung Dengan Kami?</h2>
                    <p class="lead mb-0">Daftarkan putra/putri Anda sekarang dan dapatkan pendidikan terbaik untuk masa depan mereka.</p>
                </div>
                <div class="col-lg-4 fade-in delay-1">
                    <div class="d-flex flex-column flex-md-row gap-3">
                        <a href="#" class="btn btn-light btn-lg flex-grow-1">
                            <i class="fas fa-file-alt me-2"></i> Daftar Online
                        </a>
                        <a href="{{ url('/kontak') }}" class="btn btn-outline-light btn-lg flex-grow-1">
                            <i class="fas fa-phone me-2"></i> Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>Tentang Kami</h5>
                    <p>SMP Baabussalaam adalah sekolah unggulan berbasis Aswaja di Blitar.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Jl. Abadi Dusun Kuwut RT. 01 RW. 06</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> 085649305903</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> smpibaabussalaam@gmail.com</li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/akademik') }}" class="text-white text-decoration-none">Kalender Akademik</a></li>
                        <li class="mb-2"><a href="{{ url('/guru-staff') }}" class="text-white text-decoration-none">Daftar Guru</a></li>
                        <li class="mb-2"><a href="{{ url('/fasilitas') }}" class="text-white text-decoration-none">Fasilitas Sekolah</a></li>
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
    <script src="{{ asset('assets/vendor/fontawesome/js/all.min.js') }}"></script>
    
    <script>
        // Animation Trigger with Intersection Observer
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Intersection Observer
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            // Observe all animated elements
            document.querySelectorAll('.fade-in, .scale-in').forEach(el => {
                observer.observe(el);
            });
            
            // Add hover effect to cards
            const cards = document.querySelectorAll('.card, .facility-card, .gallery-item');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('hover');
                });
                card.addEventListener('mouseleave', function() {
                    this.classList.remove('hover');
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>

</html>

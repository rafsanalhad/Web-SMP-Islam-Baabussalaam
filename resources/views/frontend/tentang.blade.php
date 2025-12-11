<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Sekolah - SMP Islam Baabussalaam</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2a9d8f;
            --primary-light: rgba(42, 157, 143, 0.1);
            --secondary: #264653;
            --accent: #e9c46a;
            --success: #4caf50;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.7;
            color: #444;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: var(--secondary);
        }
        
        section {
            padding: 5rem 0;
            position: relative;
        }
        
        .section-header {
            position: relative;
            margin-bottom: 3rem;
        }
        
        .section-header h2 {
            position: relative;
            display: inline-block;
        }
        
        .section-header h2:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            bottom: -10px;
            left: 0;
            border-radius: 2px;
        }
        
        .section-header.text-center h2:after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .about-item {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .about-item.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .delay-1 { transition-delay: 0.2s; }
        .delay-2 { transition-delay: 0.4s; }
        .delay-3 { transition-delay: 0.6s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .floating-img {
            animation: float 6s ease-in-out infinite;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border: 5px solid white;
        }
        
        .hero-about {
            background: linear-gradient(135deg, rgba(38,70,83,0.9), rgba(42,157,143,0.9)), url('{{ asset('assets/img/hero-bg.jpg') }}') no-repeat center center;
            background-size: cover;
            padding: 8rem 0;
            color: white;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-about:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(233,196,106,0.2), transparent 70%);
        }
        
        .hero-about h1 {
            font-size: 3.5rem;
            text-shadow: 0 3px 10px rgba(0,0,0,0.2);
            position: relative;
        }
        
        .hero-about .lead {
            font-size: 1.5rem;
            opacity: 0.9;
        }
        
        .breadcrumb-section {
            background: var(--light);
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0;
        }
        
        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .breadcrumb-item a:hover {
            color: var(--secondary);
        }
        
        .breadcrumb-item.active {
            color: var(--secondary);
            font-weight: 500;
        }
        
        .vm-card {
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
        }
        
        .vm-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }
        
        .vm-card:before {
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
        
        .vm-card:hover:before {
            opacity: 1;
        }
        
        .vm-card-header {
            padding: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .vm-card-header:after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
        }
        
        .vm-card-header h3 {
            position: relative;
            z-index: 2;
        }
        
        .timeline {
            position: relative;
            padding-left: 50px;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary), var(--success));
            border-radius: 2px;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .timeline-item:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -43px;
            top: 25px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: white;
            border: 4px solid var(--primary);
            box-shadow: 0 0 0 2px white;
            z-index: 2;
        }
        
        .timeline-item h4 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .hover-effect {
            transition: all 0.4s ease;
        }
        
        .hover-effect:hover {
            transform: translateY(-8px);
        }
        
        @media (max-width: 768px) {
            .hero-about {
                padding: 5rem 0;
                clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
            }
            
            .hero-about h1 {
                font-size: 2.5rem;
            }
            
            .section-header h2:after {
                width: 30%;
            }
            
            .timeline {
                padding-left: 30px;
            }
            
            .timeline-item:before {
                left: -33px;
            }
        }
        
        .navbar .nav-link {
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .navbar .nav-link:hover {
            background-color: rgba(255,255,255,0.15);
        }
        
        .navbar .nav-link.active {
            font-weight: 600;
            background-color: rgba(255,255,255,0.1);
        }
    </style>
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar text-white py-2" style="background: linear-gradient(135deg, var(--secondary), #21867a);">
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
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/logo-smp.png') }}" alt="Logo SMP Baabussalaam" class="img-fluid" style="height: 70px;">
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-8">
                    <h1 class="fw-bold mb-2" style="font-size: 1.5rem; color: var(--secondary);">SMP Islam Baabussalaam</h1>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                        <i class="fas fa-map-marker-alt me-2"></i>Jl. Abadi Dusun Kuwut RT. 01 RW. 06 Nglegok, Kab.Blitar - NPSN: 70054377
                    </p>
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
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle me-1"></i> Profil
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#sejarah"><i class="fas fa-history me-2"></i>Sejarah</a></li>
                            <li><a class="dropdown-item" href="#visi-misi"><i class="fas fa-bullseye me-2"></i>Visi Misi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#prestasi"><i class="fas fa-chart-line me-2"></i>Prestasi</a></li>
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

    <!-- Breadcrumb -->
    <section class="breadcrumb-section py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-2"></i>Beranda</a></li>
                    <li class="breadcrumb-item active">Profil Sekolah</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Hero About -->
    <section class="hero-about">
        <div class="container text-center about-item">
            <h1 class="display-3 fw-bold mb-4">Profil SMP Islam Baabussalaam</h1>
            <p class="lead mb-0">Membentuk Generasi Unggul dengan Integritas dan Intelektualitas</p>
        </div>
    </section>

    <!-- Sejarah -->
    <section class="py-5" id="sejarah">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 about-item">
                    <div class="position-relative">
                        <img src="{{ asset('assets/img/about.png') }}" class="img-fluid floating-img" alt="Sejarah" onerror="this.src='https://via.placeholder.com/600x400?text=Tentang+Kami'">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary rounded-3" style="z-index: -1; transform: translate(-15px, 15px); opacity: 0.1;"></div>
                    </div>
                </div>
                <div class="col-lg-6 about-item delay-1">
                    <div class="ps-lg-5">
                        <h2 class="fw-bold mb-4">Sejarah Singkat</h2>
                        <p class="lead mb-4">SMP Baabussalaam didirikan pada tahun 1995 dengan visi menjadi lembaga pendidikan yang memadukan keunggulan akademik dengan pembentukan karakter islami.</p>
                        <p>Bermula dari hanya 3 kelas dengan 90 siswa, kini telah berkembang menjadi sekolah unggulan dengan lebih dari 1.200 siswa. Pada tahun 2010, sekolah kami mendapatkan akreditasi A dari BAN-SM dan terus mempertahankan predikat tersebut hingga sekarang.</p>
                        <p>Tahun 2018, kami memperoleh penghargaan sebagai Sekolah Adiwiyata tingkat nasional berkat komitmen kami terhadap pendidikan lingkungan hidup.</p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="#visi-misi" class="btn btn-primary px-4">
                                <i class="fas fa-arrow-down me-2"></i> Visi & Misi
                            </a>
                            <a href="#timeline" class="btn btn-outline-primary px-4">
                                <i class="fas fa-history me-2"></i> Kilas Sejarah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi -->
    <section class="py-5 bg-light" id="visi-misi">
        <div class="container">
            <div class="section-header text-center mb-5 about-item">
                <h2 class="fw-bold">Visi & Misi</h2>
                <p class="lead text-muted">Landasan Pendidikan di SMP Baabussalaam</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 about-item">
                    <div class="card vm-card h-100">
                        <div class="vm-card-header bg-primary">
                            <h3 class="mb-0"><i class="fas fa-binoculars me-2"></i> Visi</h3>
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <p>"Terwujudnya Generasi Unggul, Berakhlak Terpuji, Menguasai Bahasa Internasional dan Berprestasi"</p>
                                <footer class="blockquote-footer mt-3">Diresmikan pada <cite>Raker 2020</cite></footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8 about-item delay-1">
                    <div class="card vm-card h-100">
                        <div class="vm-card-header bg-success">
                            <h3 class="mb-0"><i class="fas fa-bullseye me-2"></i> Misi</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-check-circle fa-lg mt-1"></i>
                                        </div>
                                        <div>
                                            <p class="mb-2">Menciptakan profil pelajar pancasila dengan mengembangkan pendidikan Islam Ahlu Sunah Waljamaah An-Nahdliyah</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-check-circle fa-lg mt-1"></i>
                                        </div>
                                        <div>
                                            <p class="mb-2">Mengintegrasikan pendidikan umum, agama dan ketrampilan serta bahasa</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-check-circle fa-lg mt-1"></i>
                                        </div>
                                        <div>
                                            <p class="mb-2">Memberikan bekal keilmuan untuk melanjutkan pendidikan lebih tinggi dengan prestasi baik</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-check-circle fa-lg mt-1"></i>
                                        </div>
                                        <div>
                                            <p class="mb-2">Menyiapkan anak didik yang mampu merealisasikan nilai-nilai agama dalam kehidupan sehari-hari</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-check-circle fa-lg mt-1"></i>
                                        </div>
                                        <div>
                                            <p class="mb-2">Memberikan bekal kecakapan hidup dengan penguatan ketrampilan bahasa Arab dan Inggris</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-check-circle fa-lg mt-1"></i>
                                        </div>
                                        <div>
                                            <p class="mb-2">Menyelenggarakan lingkungan belajar yang edukatif, rekreatif, dan islami</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="py-5" id="timeline">
        <div class="container">
            <div class="section-header text-center mb-5 about-item">
                <h2 class="fw-bold">Kilas Sejarah</h2>
                <p class="lead text-muted">Perjalanan SMP Baabussalaam</p>
            </div>
            
            <div class="timeline about-item delay-1">
                <div class="timeline-item">
                    <h4>1995</h4>
                    <p>Pendirian SMP Baabussalaam dengan 3 ruang kelas dan 5 guru. Tahun pertama menerima 90 siswa.</p>
                </div>
                
                <div class="timeline-item">
                    <h4>2002</h4>
                    <p>Pembangunan gedung baru dua lantai dengan laboratorium IPA pertama. Penambahan 6 ruang kelas baru.</p>
                </div>
                
                <div class="timeline-item">
                    <h4>2010</h4>
                    <p>Meraih akreditasi A pertama kali dari BAN-SM dengan nilai tertinggi se-kabupaten.</p>
                </div>
                
                <div class="timeline-item">
                    <h4>2015</h4>
                    <p>Peresmian program tahfidz dan pembangunan mushola baru berkapasitas 200 orang.</p>
                </div>
                
                <div class="timeline-item">
                    <h4>2018</h4>
                    <p>Penghargaan Sekolah Adiwiyata tingkat nasional untuk komitmen pendidikan lingkungan.</p>
                </div>
                
                <div class="timeline-item">
                    <h4>2022</h4>
                    <p>Pembangunan laboratorium komputer dan STEAM center dengan teknologi terkini.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Prestasi -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5 about-item">
                <h2 class="fw-bold" id="prestasi">Prestasi Sekolah</h2>
                <p class="lead text-muted">Pencapaian yang membanggakan</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4 about-item">
                    <div class="card border-0 shadow-sm h-100 hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary mb-4">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h4>Akademik</h4>
                            <ul class="text-start ps-3 mb-0">
                                <li>Juara 1 OSN tingkat kota (2018-2023)</li>
                                <li>Nilai UN tertinggi se-kabupaten 2021</li>
                                <li>10 besar OSN tingkat provinsi</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 about-item delay-1">
                    <div class="card border-0 shadow-sm h-100 hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-success bg-opacity-10 text-success mb-4">
                                <i class="fas fa-quran"></i>
                            </div>
                            <h4>Religius</h4>
                            <ul class="text-start ps-3 mb-0">
                                <li>Sekolah dengan hafidz Qur'an terbanyak</li>
                                <li>Juara 1 MTQ pelajar tingkat provinsi</li>
                                <li>Program tahfidz 32 siswa hafal 3 juz+</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 about-item delay-2">
                    <div class="card border-0 shadow-sm h-100 hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-warning bg-opacity-10 text-warning mb-4">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h4>Sains & Teknologi</h4>
                            <ul class="text-start ps-3 mb-0">
                                <li>Juara 2 Lomba Robotik Nasional 2022</li>
                                <li>Penghargaan inovasi sains nasional</li>
                                <li>STEAM center dengan teknologi terkini</li>
                            </ul>
                        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            document.querySelectorAll('.about-item').forEach(el => {
                observer.observe(el);
            });
            
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if(target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
